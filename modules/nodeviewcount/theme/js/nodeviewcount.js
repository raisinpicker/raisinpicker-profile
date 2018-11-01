jQuery(document).ready(function($) {
  var nodeviewcount_insert_node_view = function(nid, uid) {
    var nodeviewcount_path = Drupal.settings.nodeviewcount.nodeviewcount_path;
    $.ajax({
      type: 'POST',
      url: Drupal.settings.basePath + nodeviewcount_path + '/' + nid + '/' + uid,
      dataType: 'json'
    });
  }
  var nodeviewcount_nid = Drupal.settings.nodeviewcount.nodeviewcount_nid;
  var nodeviewcount_uid = Drupal.settings.nodeviewcount.nodeviewcount_uid;
  nodeviewcount_insert_node_view(nodeviewcount_nid, nodeviewcount_uid);
});