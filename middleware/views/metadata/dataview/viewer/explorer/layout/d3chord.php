<div style="background: #FFF;" id="d3-chord-<?php echo $this->dataViewId; ?>"></div>

<script type="text/javascript">
<?php
$data = array();
$list = Arr::groupByArray($this->recordList, 'srcname');

foreach ($list as $srcName => $row) {
    
    $imports = array();
    
    foreach ($row['rows'] as $import) {
        $imports[] = $import['trgname'];
    }
    
    $data[] = array(
        'name' => $srcName, 
        'size' => 10, 
        'imports' => $imports
    );
}
?>

$.cachedScript('assets/core/js/plugins/visualization/d3/d3.min.js').done(function() {
    $.cachedScript('assets/core/js/plugins/visualization/d3/chord.js').done(function() {
        dvD3ChordInit_<?php echo $this->dataViewId; ?>();
    });
});

function dvD3ChordInit_<?php echo $this->dataViewId; ?>() {
    var imports = <?php echo json_encode($data, JSON_UNESCAPED_UNICODE); ?>;
	
	var $forceDirected = $('#d3-chord-<?php echo $this->dataViewId; ?>'),
        w = $forceDirected.width(),
        h = $(window).height() - $forceDirected.offset().top - 40,
		r1 = h / 2,
		r0 = r1 - 120;

var fill = d3.scale.category20c();

var chord = d3.layout.chord()
    .padding(.04)
    .sortSubgroups(d3.descending)
    .sortChords(d3.descending);

var arc = d3.svg.arc()
    .innerRadius(r0)
    .outerRadius(r0 + 20);

var svg = d3.select("#d3-chord-<?php echo $this->dataViewId; ?>").append("svg:svg")
    .attr("width", w)
    .attr("height", h)
  .append("svg:g")
    .attr("transform", "translate(" + w / 2 + "," + h / 2 + ")");

  var indexByName = {},
      nameByIndex = {},
      matrix = [],
      n = 0;

  self.names = [];

  // Returns the Flare package name for the given class name.
  function name(name) {
    return name;
  }

  // Compute a unique index for each package name.
  imports.forEach(function(d) {
    d = name(d.name);
    if (!(d in indexByName)) {
      nameByIndex[n] = d;
      indexByName[d] = n++;
      names.push(d);
    }
  });

  // Construct a square matrix counting package imports.
  imports.forEach(function(d) {
    var source = indexByName[name(d.name)],
        row = matrix[source];
    if (!row) {
     row = matrix[source] = [];
     for (var i = -1; ++i < n;) row[i] = 0;
    }
    d.imports.forEach(function(d) { row[indexByName[name(d)]]++; });
  });

  chord.matrix(matrix);

  var g = svg.selectAll("g.group")
      .data(chord.groups)
    .enter().append("svg:g")
      .attr("class", "group")
      .on("mouseover", fade(.02))
      .on("mouseout", fade(.80));

  g.append("svg:path")
      .style("stroke", function(d) { return fill(d.index); })
      .style("fill", function(d) { return fill(d.index); })
      .attr("d", arc);

  g.append("svg:text")
      .each(function(d) { d.angle = (d.startAngle + d.endAngle) / 2; })
      .attr("dy", ".35em")
      .attr("text-anchor", function(d) { return d.angle > Math.PI ? "end" : null; })
      .attr("transform", function(d) {
        return "rotate(" + (d.angle * 180 / Math.PI - 90) + ")"
            + "translate(" + (r0 + 26) + ")"
            + (d.angle > Math.PI ? "rotate(180)" : "");
      })
      .text(function(d) { return nameByIndex[d.index].substring(0, 10)+'..'; })
	  .append("svg:title").text(function(d, i) { return nameByIndex[d.index]; });

  svg.selectAll("path.chord")
      .data(chord.chords)
    .enter().append("svg:path")
      .attr("class", "chord")
      .style("stroke", function(d) { return d3.rgb(fill(d.source.index)).darker(); })
      .style("fill", function(d) { return fill(d.source.index); })
      .attr("d", d3.svg.chord().radius(r0));

// Returns an event handler for fading a given chord group.
function fade(opacity) {
  return function(d, i) {
    svg.selectAll("path.chord")
        .filter(function(d) { return d.source.index != i && d.target.index != i; })
      .transition()
        .style("stroke-opacity", opacity)
        .style("fill-opacity", opacity);
  };
}
}
</script>