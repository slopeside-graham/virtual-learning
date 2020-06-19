// Helper function to add media
function getcustomMediaLibrary() {
  var customMediaLibrary = window.wp.media({

    // Accepts [ 'select', 'post', 'image', 'audio', 'video' ]
    // Determines what kind of library should be rendered.
    frame: 'select',

    // Modal title.
    title: "'Select Images'",

    // Enable/disable multiple select
    multiple: false,

    // Library wordpress query arguments.
    library: {
      order: 'DESC',

      // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo', 'id', 'post__in', 'menuOrder' ]
      orderby: 'date',

      // mime type. e.g. 'image', 'image/jpeg'
      type: 'image',

      // Searches the attachment title.
      search: null,

      // Includes media only uploaded to the specified post (ID)
      uploadedTo: null // wp.media.view.settings.post.id (for current post ID)
    },

    button: {
      text: 'Done'
    }

  }
  );
  return customMediaLibrary;
}

function displayLoading(target) {
  var element = $(target);
  kendo.ui.progress(element, true);
}
function hideLoading(target) {
  var element = $(target);
  kendo.ui.progress(element, false);
}