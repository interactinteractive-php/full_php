<script src="../api/pdf/build/2.3.136/pdf.js"></script>
<select name="pageSelector" id="pageSelector" style="position: absolute; top: 0; left: 0">
  <option value="1">Page 1</option>
</select>
<canvas style="position: absolute; top: 20; left: 0; border:1px solid #000000;" id="mask_canvas" width="918/2" height="1188/2">
  &nbsp;
</canvas>
<canvas style="position: absolute; top: 20; left: 0; border:1px solid #000000;" id="test_canvas" width="918/2" height="1188/2">
  <!-- <p>Your browser does not support <a href="http://www.w3.org/html/wg/html5/">HTML5</a> &lt;canvas&gt; elements!</p> -->
</canvas>
<!-- <button style="position: relative;" onclick="sendSelectedPosition()">Сонгох</button> -->
<script src='../assets/core/js/main/jquery.min.js'></script>
<script type="text/javascript">
  var dollParts = {
    "layers": [
      // {"id": "bg", "group": "bg", "width":500, "height":700, "top":20, "left":40, "img":'', "bg":''},
      {
        "id": "dual_red",
        "group": "dual_layer",
        "width": 130,
        "height": 60,
        "top": 484,
        "left": 168,
        "img": '',
        "bg": 'rgba(200,0,0, 0.5)'
      },
    ],
    "snaps": [{
      "from": "blot",
      "to": "single_layer",
      "left": 30,
      "top": 30
    }, ],
  };
  var dragData = {
    "start_x": 0,
    "start_y": 0,
    "group": "",
    "lastSnap": ""
  };
  var dragResult = {
    'x': 0,
    'y': 0,
    'groups': [],
    'layers': []
  };
  var img;
  var img_cache = []; // For holding the image datas
  var stage_width = 918 / 2;
  var stage_height = 1188 / 2;

  var selPosX = 0;
  var selPosY = 0;

  // Draws all the doll layers to the stage
  function drawLayers(ctx) {
    ctx.clearRect(0, 0, stage_width, stage_height); // Clear the stage
    for (var i in dollParts.layers) {
      layer = dollParts.layers[i];
      ctx.fillStyle = layer.bg;
      var background = new Image();
      // background.src = document.getElementById('the-canvas').toDataURL();
      ctx.fillRect(layer.left, layer.top, layer.width, layer.height);
      if (layer.img != "") {
        ctx.drawImage(img_cache[layer.id], layer.left, layer.top);
      }
    }
    // drawStatus(ctx);
  }

  // Sets the source of a layer with the specified ID to the specified image source
  function setSrc(id, src) {
    for (var i in dollParts.layers) {
      if (dollParts.layers[i].id == id) {
        dollParts.layers[i].src = src;
        return true;
      }
    }
    return false;
  }

  // Returns true if array 'arr' contains at least one element matching 'search'
  function in_array(arr, search) {
    for (var i = 0; i < arr.length; i++) {
      if (arr[i] == search) {
        return true;
      }
    }
    return false;
  }

  // Removes all duplicate entries from array 'arr' and returns result
  function array_unique(arr) {
    tmp = new Array();
    for (var i = 0; i < arr.length; i++) {
      if (!in_array(tmp, arr[i])) {
        tmp.push(arr[i]);
      }
    }
    return tmp;
  }

  function sendSelectedPosition() {
    console.log(selPosY, selPosX);
  }

  window.addEventListener('message', function(event) {
    var origin = event.origin || event.originalEvent.origin; // For Chrome, the origin property is in the event.originalEvent object.
    // if (origin !== /*the container's domain url*/)
    // return;
    if (typeof event.data == 'object' && event.data.call == 'canvasClickSendValue_<?php echo $this->uniqid ?>') {

      parent.postMessage({
        type: 'eventFromCanvas_<?php echo $this->uniqid ?>',
        value: {
          x: selPosX,
          y: selPosY,
          path: event.data.value,
          pageNum: parseInt($("#pageSelector").val())
        }
      });
      console.log(selPosY, selPosX);
      // Do something with event.data.value;
    }
  }, false);

  $(document).ready(function() {
    var pdf = {};
    $("#pageSelector").change(function() {
      console.log(pdf);
      pageNumber = parseInt($("#pageSelector").val());
      var url = '../<?php echo $this->pdfPath; ?>';
      var pdfjsLib = window['pdfjs-dist/build/pdf'];
      console.log(pdfjsLib.GlobalWorkerOptions);

      pdfjsLib.GlobalWorkerOptions.workerSrc = '../api/pdf/build/2.3.136/pdf.worker.js';
      var loadingTask = pdfjsLib.getDocument(url);
      loadingTask.promise.then(function(pdf) {  
        pdf.getPage(pageNumber).then(function(page) {
          var scale = 0.75;
          var viewport = page.getViewport({
            scale: scale
          });

          var canv = document.getElementById('mask_canvas');
          var context = canv.getContext('2d');
          canv.height = 1188 / 2;
          canv.width = 918 / 2;

          // Render PDF page into canvas context
          var renderContext = {
            canvasContext: context,
            viewport: viewport
          };

          rContext = renderContext;
          var renderTask = page.render(renderContext);
          renderTask.promise.then(function() {
            console.log('Page rendered');
          });
        });


      }, function(reason) {
        // PDF loading error
        console.error(reason);
      });
      // pdf.getPage(pageNumber).then(function(page) {
      //   var scale = 0.75;
      //   var viewport = page.getViewport({
      //     scale: scale
      //   });

      //   var canv = document.getElementById('mask_canvas');
      //   var context = canv.getContext('2d');
      //   canv.height = 1188 / 2;
      //   canv.width = 918 / 2;

      //   // Render PDF page into canvas context
      //   var renderContext = {
      //     canvasContext: context,
      //     viewport: viewport
      //   };

      //   rContext = renderContext;
      //   var renderTask = page.render(renderContext);
      //   renderTask.promise.then(function() {
      //     console.log('Page rendered');
      //   });
      // });
    });

    var canvas = $("#test_canvas");
    canvas = canvas[0];
    canvas.height = 1188 / 2;
    canvas.width = 918 / 2;
    var rContext;

    // var url = 'https://raw.githubusercontent.com/mozilla/pdf.js/ba2edeae/examples/learning/helloworld.pdf';
    // var url = 'https://raw.githubusercontent.com/mozilla/pdf.js/ba2edeae/master/web/compressed.tracemonkey-pldi-09.pdf';
    // https://raw.githubusercontent.com/mozilla/pdf.js/blob/master/web/compressed.tracemonkey-pldi-09.pdf

    var url = '../<?php echo $this->pdfPath; ?>';
    var pdfjsLib = window['pdfjs-dist/build/pdf'];
    console.log(pdfjsLib.GlobalWorkerOptions);

    pdfjsLib.GlobalWorkerOptions.workerSrc = '../api/pdf/build/2.3.136/pdf.worker.js';
    var loadingTask = pdfjsLib.getDocument(url);
    loadingTask.promise.then(function(pdf) {
      if (pdf._pdfInfo.numPages > 0) {
        for (index = 2; index <= pdf._pdfInfo.numPages; index++) {
          $("#pageSelector").append(new Option('Page ' + index, index));
        }
      }
      var pageNumber = 1;
      pdf = pdf;
      pdf.getPage(pageNumber).then(function(page) {
        var scale = 0.75;
        var viewport = page.getViewport({
          scale: scale
        });

        var canv = document.getElementById('mask_canvas');
        var context = canv.getContext('2d');
        canv.height = 1188 / 2;
        canv.width = 918 / 2;

        // Render PDF page into canvas context
        var renderContext = {
          canvasContext: context,
          viewport: viewport
        };

        rContext = renderContext;
        var renderTask = page.render(renderContext);
        renderTask.promise.then(function() {
          console.log('Page rendered');
        });
      });


    }, function(reason) {
      // PDF loading error
      console.error(reason);
    });

    if (canvas.getContext) {
      // Browser supports Canvas elements
      var ctx = canvas.getContext('2d');
      // Cache the images
      for (var i in dollParts.layers) {
        if (dollParts.layers[i].img != "") {
          id = dollParts.layers[i].id;
          img_cache[id] = new Image();
          img_cache[id].crossOrigin = ''; // Chrome cross-origin flag
          img_cache[id].src = dollParts.layers[i].img;
        }
      }

      // Assign target groups to triggers
      for (var i in dollParts.triggers) {
        id = dollParts.triggers[i].target;
        for (var j in dollParts.layers) {
          if (dollParts.layers[j].id == id) {
            dollParts.triggers[i].targetGroup = dollParts.layers[j].group;
            break; // Jump out of for loop
          }
        }
      }

      drawLayers(ctx); // Draw layers in initial positions

      $("#test_canvas").bind("mousedown", function(e) {
        var rel_x = e.clientX - this.offsetTop - parseInt($("#test_canvas").css('border-top-width'));
        var rel_y = e.clientY - this.offsetLeft - parseInt($("#test_canvas").css('border-left-width'));
        if (typeof console != "undefined") console.log("x:" + rel_x + ", y:" + rel_y); // Where did this click happen, relative to canvas?

        var top_layer;
        for (var i = dollParts.layers.length - 1; i >= 0; i--) {
          // Loop through layers top down
          layer = dollParts.layers[i];
          if (rel_x >= layer.left && rel_x <= layer.left + layer.width && rel_y >= layer.top && rel_y <= layer.top + layer.height) {
            // This click is within this layer
            if (layer.img != "") {
              // Check for transparency
              var mask = $("#mask_canvas");
              var mask_ctx = mask[0].getContext('2d');
              mask_ctx.clearRect(0, 0, layer.width, layer.height);
              mask_ctx.fillStyle = "rgb(0,0,0)";
              mask_ctx.drawImage(img_cache[layer.id], 0, 0);
              mask_img = mask_ctx.getImageData(0, 0, layer.width, layer.height);
              tmp_x = rel_x - layer.left;
              tmp_y = rel_y - layer.top;
              alpha = mask_img.data[((tmp_y * (mask_img.width * 4)) + (tmp_x * 4)) + 3];
              //if (typeof console != "undefined") console.log("alpha is "+alpha);
              if (alpha > 10) {
                // Opaque here
                top_layer = layer;
                break; // Jump out of for loop
              }
            } else {
              top_layer = layer;
              break; // Jump out of for loop
            }
          }
        }
        if (typeof top_layer == "object") {
          // There is a layer under that click
          dragData.start_x = rel_x;
          dragData.start_y = rel_y;
          dragData.group = top_layer.group;
        }
      });

      $("#test_canvas").bind("mousemove", function(e) {
        if (dragData.group != "") {
          // Drag in progress
          var rel_x = e.clientX - this.offsetTop - parseInt($("#test_canvas").css('border-top-width'));
          var rel_y = e.clientY - this.offsetLeft - parseInt($("#test_canvas").css('border-left-width'));

          var delta_x = rel_x - dragData.start_x;
          var delta_y = rel_y - dragData.start_y;
          // First see if this places a layer in the group outside the canvas
          for (var i in dollParts.layers) {
            layer = dollParts.layers[i];
            if (layer.group == dragData.group) {
              // Layer is in the group that's moving
              //if (typeof console != "undefined") console.info(layer.left+"+"+delta_x+"+"+layer.width+" = "+(layer.left+delta_x+layer.width)+" vs. "+stage_width);
              //if (typeof console != "undefined") console.info(layer.top+"+"+delta_y+"+"+layer.height+" = "+(layer.top+delta_y+layer.height)+" vs. "+stage_height);
              if (layer.left + delta_x + layer.width > stage_width) {
                //if (typeof console != "undefined") console.log("Too far right!");
                delta_x = stage_width - layer.width - layer.left;
              } else if (layer.left + delta_x < 0) {
                //if (typeof console != "undefined") console.log("Too far left!");
                delta_x = 0 - layer.left;
              }
              if (layer.top + delta_y + layer.height > stage_height) {
                //if (typeof console != "undefined") console.log("Too far down!");
                delta_y = stage_height - layer.height - layer.top;
              } else if (layer.top + delta_y < 0) {
                //if (typeof console != "undefined") console.log("Too far up!");
                delta_y = 0 - layer.top;
              }
            }
          }

          // Move the layers
          for (var i in dollParts.layers) {
            layer = dollParts.layers[i];
            if (layer.group == dragData.group) {
              layer.left = layer.left + delta_x;
              layer.top = layer.top + delta_y;
            }
          }
          dragData.start_x = rel_x;
          dragData.start_y = rel_y;

          // Update the display
          drawLayers(ctx);

        }

      });

      $("body").bind("mouseup", function(e) {
        if (dragData.group != "") {
          // We were dragging; wrap it up
          dragResult = {
            'x': 0,
            'y': 0,
            'groups': [],
            'layers': []
          }; // Clear existing

          dragResult.x = e.clientX - this.offsetTop - parseInt($("#test_canvas").css('border-top-width'));
          dragResult.y = e.clientY - this.offsetLeft - parseInt($("#test_canvas").css('border-left-width'));

          if (dragResult.x > 0 && dragResult.y > 0 && dragResult.x <= stage_width && dragResult.y <= stage_height) {
            // Find all layers/groups under drop point
            for (var i in dollParts.layers) {
              layer = dollParts.layers[i];
              if (dragResult.x >= layer.left && dragResult.x <= layer.left + layer.width && dragResult.y >= layer.top && dragResult.y <= layer.top + layer.height) {
                // This click is within this layer
                if (layer.group != dragData.group) {
                  if (layer.img != "") {
                    // Check for transparency
                    var mask = $("#mask_canvas");
                    var mask_ctx = mask[0].getContext('2d');
                    mask_ctx.clearRect(0, 0, layer.width, layer.height);
                    mask_ctx.fillStyle = "rgb(0,0,0)";
                    mask_ctx.drawImage(img_cache[layer.id], 0, 0);
                    mask_img = mask_ctx.getImageData(0, 0, layer.width, layer.height);
                    tmp_x = dragResult.x - layer.left;
                    tmp_y = dragResult.y - layer.top;
                    alpha = mask_img.data[((tmp_y * (mask_img.width * 4)) + (tmp_x * 4)) + 3];
                    //if (typeof console != "undefined") console.log("alpha is "+alpha);
                    if (alpha > 10) {
                      // Opaque here
                      dragResult.groups.unshift(layer.group);
                      dragResult.layers.unshift(layer.id);
                    }
                  } else {
                    dragResult.groups.unshift(layer.group);
                    dragResult.layers.unshift(layer.id);
                  }
                }
              }
            }
          } else {
            // Drop point was outside canvas
            dragResult.groups = [];
            dragResult.layers = [];
          }
          dragResult.groups = array_unique(dragResult.groups); // Remove doubles


          // Check for snap
          if (dragResult.x > 0 && dragResult.y > 0 && dragResult.x <= stage_width && dragResult.y <= stage_height) {
            // Drag ended within the canvas

            group_loop: for (var i in dragResult.groups) {
              // Loop through groups that are under the drop spot, from top down
              if (dragResult.groups[i] != dragData.lastSnap) {
                // Not the same group we snapped to last time; see if any snaps apply
                var cur_snap;
                for (var j in dollParts.snaps) {
                  snap = dollParts.snaps[j];
                  if (snap.from == dragData.group && snap.to == dragResult.groups[i]) {
                    // This snap applies
                    cur_snap = snap;
                    break group_loop; // Jump out of the for loop
                  }
                }
              }
            }
            if (typeof cur_snap == "object") {
              // There's a snap that applies; find the topmost layer of the target group
              for (var i = dollParts.layers.length - 1; i >= 0; i--) {
                layer = dollParts.layers[i];
                if (layer.group == cur_snap.to) {
                  // Group match; calculate the snap destination
                  var target_x = layer.left + snap.left;
                  var target_y = layer.top + snap.top;
                  break; // Jump out of the for loop
                }
              }
              // Move all elements of the "from" group to the snapped position
              var delta_x = "null";
              var delta_y = "null";
              for (var i = dollParts.layers.length - 1; i >= 0; i--) {
                layer = dollParts.layers[i];
                if (layer.group == cur_snap.from) {
                  // Group match
                  if (delta_x == "null") {
                    // This is the first layer from this group; determine offset
                    // console.log(target_x, target_y);
                    delta_x = target_x - layer.left;
                    delta_y = target_y - layer.top;
                    // console.log(delta_x, delta_y);
                  }
                  // Move the layer
                  layer.left = layer.left + delta_x;
                  layer.top = layer.top + delta_y;

                  // console.log(layer.left, layer.top);
                }
              }

              // Record the snap
              dragData.lastSnap = cur_snap.to;
            } else {
              // No snaps apply to this drop
              dragData.lastSnap = "";
            }
          }
          else {
            // Drag ended outside stage
            dragData.lastSnap = "";
          }
          console.log(dollParts.layers[0].left);
          console.log(dollParts.layers[0].top);
          selPosY = dollParts.layers[0].top;
          selPosX = dollParts.layers[0].left;
          // Check for triggers
          // Stop the drag
          dragData.start_x = 0;
          dragData.start_y = 0;
          dragData.group = "";
          drawLayers(ctx);
        }
      });

    } else {
      if (console) {
        console.error("No Canvas Support!");
      }
    }
  })
</script>

<!-- </body> -->
<!-- </html>