(function($){
  "use strict";

var s, i, filter, data,
    colors = [ '#F44336',
               '#F57C00',
               '#FFB300',
               '#9E9D24',
               '#388E3C',
               '#009688',
               '#2196F3',
               '#3F51B5',
               '#673AB7'],
    icons = { knowledge: "lightbulb_outline",
              publication: "book",
              person: "person",
              event: "event",
              collection: "view_headline" };
var A = $.getJSON('/api/graph-nodes');
var B = $.getJSON('/api/graph-edges');
sigma.prototype.zoomToNode = function(node, ratio, camera){
  if(typeof camera == "undefined"){
      camera = this.cameras[0];
  }
  camera.ratio = ratio;
  camera.x = node[camera.readPrefix+"x"];
  camera.y = node[camera.readPrefix+"y"]; 
  this.refresh();
}
var _ = {
  $: function (id) {
    return document.getElementById(id);
  },
  all: function (selectors) {
    return document.querySelectorAll(selectors);
  },
  removeClass: function(selectors, cssClass) {
    var nodes = document.querySelectorAll(selectors);
    var l = nodes.length;
    for ( i = 0 ; i < l; i++ ) {
      var el = nodes[i];
      // Bootstrap compatibility
      el.className = el.className.replace(cssClass, '');
    }
  },
  addClass: function (selectors, cssClass) {
    var nodes = document.querySelectorAll(selectors);
    var l = nodes.length;
    for ( i = 0 ; i < l; i++ ) {
      var el = nodes[i];
      // Bootstrap compatibility
      if (-1 == el.className.indexOf(cssClass)) {
        el.className += ' ' + cssClass;
      }
    }
  },
  show: function (selectors) {
    this.removeClass(selectors, 'hidden');
  },
  hide: function (selectors) {
    this.addClass(selectors, 'hidden');
  },
  toggle: function (selectors, cssClass) {
    var cssClass = cssClass || "hidden";
    var nodes = document.querySelectorAll(selectors);
    var l = nodes.length;
    for ( i = 0 ; i < l; i++ ) {
      var el = nodes[i];
      //el.style.display = (el.style.display != 'none' ? 'none' : '' );
      // Bootstrap compatibility
      if (-1 !== el.className.indexOf(cssClass)) {
        el.className = el.className.replace(cssClass, '');
      } else {
        el.className += ' ' + cssClass;
      }
    }
  }
};

function updatePane (graph, filter) {
  var maxDegree = 20;
  var options = '';
  graph.nodes().forEach(function(n) {
    options += '<option data-value="'+n.id+'" />'+n.label;
    document.getElementById('suggestionList').innerHTML = options;
  });
  // graph.nodes().forEach(function(n) {
   // maxDegree = Math.max(maxDegree, graph.degree(n.id));
 // })
  _.$('min-degree').max = maxDegree;
//  _.$('max-degree-value').textContent = maxDegree;
  _.$('reset-btn').addEventListener("click", function(e) {
    _.$('min-degree').value = 0;
    _.$('min-degree-val').textContent = '0';
    _.$('node-rating').value = 0;
    _.$('min-rating-val').textContent = '0';
    filter.undo().apply();
    _.$('dump').textContent = '';
    _.hide('#dump');
  });
  _.$('force-btn').addEventListener("click", function(e) {
    if(sigma.layouts.isForceAtlas2Running()) {
      _.$('force-btn').textContent = "Stop Layout";
      sigma.layouts.stopForceAtlas2();
    } else {
      _.$('force-btn').textContent = "Start Layout";
      sigma.layouts.startForceAtlas2(s);
    }
  });
}

$.when(A,B).done(function(aResult, bResult){//when all request are successful
  data = Object.assign({}, aResult[0], bResult[0]);
  for (i = 0; i < data.nodes.length; i++) {
    data.nodes[i].x = Math.random(); // 100 * Math.cos(2 * i * Math.PI / l);
    data.nodes[i].y = Math.random(); // 100 * Math.sin(2 * i * Math.PI / l);
    data.nodes[i].size =  Math.min(3 * (data.nodes[i].rids + 4),50);
    data.nodes[i].color = colors[data.nodes[i].rating-1];
    data.nodes[i].icon = {
      font: 'Material Icons',
      scale: 1.0, 
      color: '#fff',
      content: icons[data.nodes[i].ctype]
    };
  }
  for (i = 0; i < data.edges.length; i++) {
    data.edges[i].size =  Math.max(data.edges[i].rating,1);
    data.edges[i].color = 'rgba(38, 50, 56, 0.3)';
    data.edges[i].hover_color = 'rgba(38, 50, 56, 1)';
  }
  s = new sigma({
    graph: data,
     renderers: [{
      container: document.getElementById('graph-container'),
      type: 'canvas' // sigma.renderers.canvas works as well
    }],
    settings: {
      defaultEdgeColor: '#rgba(38, 50, 56, 0.2)',
      defaultLabelColor: '#37474F',
      defaultNodeColor: '#607D8B',
      defaultNodeBorderColor: '#37474F',
      labelHoverShadowColor: '#263238',
      nodeHoverColor: 'default',
      defaultNodeHoverColor: '#263238',
      defaultHoverLabelBGColor: '#ECEFF1',
      defaultLabelHoverColor: '#263238',
      defaultEdgeHoverColor: '#263238',
  //    nodeBorderSize: 2,
  //    nodeHoverBorderSize: 2,
  //    nodeActiveBorderSize: 2,
      defaultLabelSize: 10,
      edgeHoverSizeRatio: 1,
      enableHovering: true,
      enableEdgeHovering: true,
      drawEdgeLabels: true,
      edgeHoverExtremities: true,
      edgeLabelSize: 'fixed',
      edgeLabelSizePowRatio: 1,
      edgeLabelThreshold: 6,
   //   doubleClickEnabled: false,
      minEdgeSize: 0.5,
      maxEdgeSize: 4,
      edgeHoverColor: 'edge',
      animationsTime: 2500
    }
  });
  var fa = sigma.layouts.startForceAtlas2(s,{
      worker: true, 
      barnesHutOptimize: true,
      startingIterations: 1,
      iterationsPerRender: 1,
      slowDown: 15,
      gravity: 35,
      autoStop: true,
      maxIterations: 100
    //  background: false,
     // easing: 'cubicInOut'
  });
  fa.bind('start stop interpolate', function(e) {
    console.log(e.type);
    if (e.type === 'start') {
      // $('#notice').className = '';
      //$('#force-btn').addClass('hidden');
    }
    else if (e.type === 'interpolate') {
     // $('#notice').className = '';
    //  $('#force-btn').className = 'hidden';
    }
    else if (e.type === 'stop') {
      $('#notice').addClass('hidden');
      $('#force-btn').removeClass('hidden');
    }
  });
  filter = new sigma.plugins.filter(s);
  updatePane(s.graph, filter);
  function applyMinDegreeFilter(e) {
    var v = e.target.value;
    _.$('min-degree-val').textContent = v;
    filter
      .undo('min-degree')
      .nodesBy(function(n) {
        return this.degree(n.id) >= v;
      }, 'min-degree')
      .apply();
  }
  function applyRatingFilter(e) {
    var c = e.target.value;
    _.$('min-rating-val').textContent = c;
    filter
      .undo('node-rating')
      .nodesBy(function(n) {
        return n.rating >= c;
      }, 'node-rating')
      .apply();
  }
  _.$('min-degree').addEventListener("input", applyMinDegreeFilter);  
  _.$('node-rating').addEventListener("input", applyRatingFilter);
  var dragListener = sigma.plugins.dragNodes(s, s.renderers[0]);
  document.querySelector('input[list]').addEventListener('input', function(e) {
    var input = e.target,
      list = input.getAttribute('list'),
      options = document.querySelectorAll('#' + list + ' option'),
      hiddenInput = document.getElementById(input.id + '-hidden'),
      inputValue = input.value;
    hiddenInput.value = inputValue;
    for(var i = 0; i < options.length; i++) {
      var option = options[i];
      if(option.innerText === inputValue) {
        // hiddenInput.value = option.getAttribute('data-value');
        console.log(option.getAttribute('data-value'));
        s.zoomToNode(s.graph.nodes()[option.getAttribute('data-value')], 0.05);
        break;
      }
    }
  });
});


})(jQuery);