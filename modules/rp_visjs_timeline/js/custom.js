
  // create groups
  var numberOfGroups = 25; 
  var groups = new vis.DataSet()
  for (var i = 0; i < numberOfGroups; i++) {
    groups.add({
      id: i,
      content: 'Truck&nbsp;' + i
    })
  }
  
  // create items
  var numberOfItems = 1000;
  var items = new vis.DataSet();
  var itemsPerGroup = Math.round(numberOfItems/numberOfGroups);
  for (var truck = 0; truck < numberOfGroups; truck++) {
    var date = new Date();
    for (var order = 0; order < itemsPerGroup; order++) {
      date.setHours(date.getHours() +  4 * (Math.random() < 0.2));
      var start = new Date(date);
      date.setHours(date.getHours() + 2 + Math.floor(Math.random()*4));
      var end = new Date(date);
      var orderIndex = order + itemsPerGroup * truck
      items.add({
        id: orderIndex,
        group: truck,
        start: start,
        end: end,
        content: 'Order ' + orderIndex
      });
    }
  }
  
  // specify options
  var options = {
    stack: true,
    verticalScroll: true,
    zoomKey: 'ctrlKey',
    maxHeight: 800,
    start: new Date(),
    end: new Date(1000*60*60*24 + (new Date()).valueOf()),
  };
  // create a Timeline
  options1 = jQuery.extend(options, {});
  var container1 = document.getElementById('mytimeline1');
  timeline1 = new vis.Timeline(container1, items, groups, options1);