<div class="col-md-8">
    <script id="code">
  function init() {
    var $ = go.GraphObject.make;  // for conciseness in defining templates
    myDiagram =
      $(go.Diagram, "myDiagram",  // must name or refer to the DIV HTML element
        {
          initialContentAlignment: go.Spot.Center,
          allowDrop: true,  // must be true to accept drops from the Palette
          "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
          "LinkRelinked": showLinkLabel,
          "animationManager.duration": 800, // slightly longer than default (600ms) animation
          "undoManager.isEnabled": true  // enable undo & redo
        });
    // when the document is modified, add a "*" to the title and enable the "Save" button
    myDiagram.addDiagramListener("Modified", function(e) {
        var button = document.getElementById("SaveButton");
        if (button) button.disabled = !myDiagram.isModified;
        var idx = document.title.indexOf("*");
        if (myDiagram.isModified) {
          if (idx < 0) document.title += "*";
        } else {
          if (idx >= 0) document.title = document.title.substr(0, idx);
        }
    });

    // helper definitions for node templates

    function nodeStyle() {
        return [
            // The Node.location comes from the "loc" property of the node data,
            // converted by the Point.parse static method.
            // If the Node.location is changed, it updates the "loc" property of the node data,
            // converting back using the Point.stringify static method.
            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
            {
              // the Node.location is at the center of each node
              locationSpot: go.Spot.Center,
              //isShadowed: true,
              //shadowColor: "#888",
              // handle mouse enter/leave events to show/hide the ports
              mouseEnter: function (e, obj) { showPorts(obj.part, true); },
              mouseLeave: function (e, obj) { showPorts(obj.part, false); }
            }
        ];
    }

    // Define a function for creating a "port" that is normally transparent.
    // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
    // and where the port is positioned on the node, and the boolean "output" and "input" arguments
    // control whether the user can draw links from or to the port.
    function makePort(name, spot, output, input) {
      // the port is basically just a small circle that has a white stroke when it is made visible
      return $(go.Shape, "Circle",
               {
                  fill: "transparent",
                  stroke: null,  // this is changed to "white" in the showPorts function
                  desiredSize: new go.Size(10, 10),
                  alignment: spot, alignmentFocus: spot,  // align the port on the main Shape
                  portId: name,  // declare this object to be a "port"
                  fromSpot: spot, toSpot: spot,  // declare where links may connect at this port
                  fromLinkable: output, toLinkable: input,  // declare whether the user may draw links to/from here
                  cursor: "pointer"  // show a different cursor to indicate potential link point
               });
    }

    // define the Node templates for regular nodes

    var lightText = 'whitesmoke';

    myDiagram.nodeTemplateMap.add("",  // the default category
        $(go.Node, "Spot", nodeStyle(),
            // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
            $(go.Panel, "Auto",
              $(go.Shape, "Rectangle",
                { fill: "#00A9C9", stroke: null },
                new go.Binding("figure", "figure")),
              $(go.TextBlock,
                {
                  font: "bold 11pt Helvetica, Arial, sans-serif",
                  stroke: lightText,
                  margin: 8,
                  maxSize: new go.Size(160, NaN),
                  wrap: go.TextBlock.WrapFit,
                  editable: true
                },
                new go.Binding("text", "text").makeTwoWay())
            ),
            // four named ports, one on each side:
            makePort("T", go.Spot.Top, false, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, false)
        ));

    myDiagram.nodeTemplateMap.add("Start",
      $(go.Node, "Spot", nodeStyle(),
        $(go.Panel, "Auto",
          $(go.Shape, "Circle",
            { minSize: new go.Size(40, 60), fill: "#79C900", stroke: null }),
          $(go.TextBlock, "Start",
            { margin: 5, font: "bold 11pt Helvetica, Arial, sans-serif", stroke: lightText })
        ),
        // three named ports, one on each side except the top, all output only:
        makePort("L", go.Spot.Left, true, false),
        makePort("R", go.Spot.Right, true, false),
        makePort("B", go.Spot.Bottom, true, false)
      ));

    myDiagram.nodeTemplateMap.add("End",
      $(go.Node, "Spot", nodeStyle(),
        $(go.Panel, "Auto",
          $(go.Shape, "Circle",
            { minSize: new go.Size(40, 60), fill: "#DC3C00", stroke: null }),
          $(go.TextBlock, "End",
            { margin: 5, font: "bold 11pt Helvetica, Arial, sans-serif", stroke: lightText })
        ),
        // three named ports, one on each side except the bottom, all input only:
        makePort("T", go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, false, true),
        makePort("R", go.Spot.Right, false, true)
      ));

    myDiagram.nodeTemplateMap.add("Comment",
      $(go.Node, "Auto", nodeStyle(),
        $(go.Shape, "File",
          { fill: "#EFFAB4", stroke: null }),
        $(go.TextBlock,
          {
            margin: 5,
            maxSize: new go.Size(200, NaN),
            wrap: go.TextBlock.WrapFit,
            textAlign: "center",
            editable: true,
            font: "bold 12pt Helvetica, Arial, sans-serif",
            stroke: '#454545'
          },
          new go.Binding("text", "text").makeTwoWay())
        // no ports, because no links are allowed to connect with a comment
      ));


    // replace the default Link template in the linkTemplateMap
    myDiagram.linkTemplate =
      $(go.Link,  // the whole link panel
        {
          routing: go.Link.AvoidsNodes,
          curve: go.Link.JumpOver,
          corner: 5, toShortLength: 4,
          relinkableFrom: true,
          relinkableTo: true,
          reshapable: true
        },
        new go.Binding("points").makeTwoWay(),
        $(go.Shape,  // the link path shape
          { isPanelMain: true, stroke: "gray", strokeWidth: 2 }),
        $(go.Shape,  // the arrowhead
          { toArrow: "standard", stroke: null, fill: "gray"}),
        $(go.Panel, "Auto",  // the link label, normally not visible
          { visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
          new go.Binding("visible", "visible").makeTwoWay(),
          $(go.Shape, "RoundedRectangle",  // the label shape
            { fill: "#F8F8F8", stroke: null }),
          $(go.TextBlock, "Yes",  // the label
            {
              textAlign: "center",
              font: "10pt helvetica, arial, sans-serif",
              stroke: "#333333",
              editable: true
            },
            new go.Binding("text", "text").makeTwoWay())
        )
      );

    // Make link labels visible if coming out of a "conditional" node.
    // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
    function showLinkLabel(e) {
        var label = e.subject.findObject("LABEL");
        if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Diamond");
    }

    // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
    myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
    myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

    // initialize the Palette that is on the left side of the page
    myPalette =
      $(go.Palette, "myPalette",  // must name or refer to the DIV HTML element
        {
          "animationManager.duration": 800, // slightly longer than default (600ms) animation
          nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
          model: new go.GraphLinksModel([  // specify the contents of the Palette
            { category: "Start", text: "Start" },
            { text: "Step" },
            { text: "???", figure: "Diamond" },
            { category: "End", text: "End" },
            { category: "Comment", text: "Comment", figure: "RoundedRectangle" }
          ])
        });

  }

  // Make all ports on a node visible when the mouse is over the node
  function showPorts(node, show) {
    var diagram = node.diagram;
    if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
    node.ports.each(function(port) {
        port.stroke = (show ? "white" : null);
      });
  }
  // Show the diagram's model in JSON format that the user may edit
  function save() {
    document.getElementById("mySavedModel").value = myDiagram.model.toJson();
    myDiagram.isModified = false;
  }
  function load() {
      $.getJSON("http://localhost/mygojs/data.json", function(data){
          myDiagram.model = go.Model.fromJson(data);
      });
  }
</script>
</head>
<body onload="init()">
    <div style="width:100%; white-space:nowrap;">
        <span style="display: inline-block; vertical-align: top; padding: 5px; width:100px">
            <div id="myPalette" style="border: solid 1px gray; height: 720px"></div>
        </span>

        <span style="display: inline-block; vertical-align: top; padding: 5px; width:90%">
            <div id="myDiagram" style="border: solid 1px gray; height: 720px"></div>
        </span>
    </div>
    <button id="SaveButton" onclick="save()">Save</button>
    <button onclick="load()">Load</button>
    <textarea id="mySavedModel" style="width:100%;height:300px"></textarea>
    <div>----------------------------------------------</div>
    
    <script>
        $(function(){
            $('#myDiagram').on('click', function(){
                console.log($(this).html());
            });
        });
    </script>

</div>

<div class="col-md-2">
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">

            <div class="card light shadow">
                <div class="card-header card-header-no-padding header-elements-inline">
                    <div class="card-title">
                        <i class="fa fa-tasks"></i>
                        <span class="caption-subject font-weight-bold"><a href="javascript:;">Тохиргоо</a></span>
                    </div>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group mb0">
                        <?php echo Form::select(array(
                                    'name' => 'META_DATA_ID', 
                                    'id' => 'META_DATA_ID', 
                                    'class' => 'form-control select2me',
                                    'data' => $this->getMetaDataList, 
                                    'op_value' => 'META_DATA_ID', 
                                    'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME',//ASSET_KEY_ID| |
                                    'data-placeholder' => 'Мета',
                                    'text' => ' ',
                                    'required'=>'required'
                                    )); 
                        ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>