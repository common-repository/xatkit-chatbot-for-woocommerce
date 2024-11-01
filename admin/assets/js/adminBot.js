
jQuery(document).ready(function ($) {

  // Uploading files
  var file_frame;
  var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id


  jQuery('#upload_image_button').on('click', function (event) {

    event.preventDefault();

    // If the media frame already exists, reopen it.
    if (file_frame) {
      // Set the post ID to what we want
      file_frame.uploader.uploader.param('post_id', set_to_post_id);
      // Open frame
      file_frame.open();
      return;
    } else {
      // Set the wp.media post id so the uploader grabs the ID we want when initialised
      wp.media.model.settings.post.id = set_to_post_id;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: 'Select a image to upload',
      button: {
        text: 'Use this image',
      },
      multiple: false	// Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    file_frame.on('select', function () {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();

      // Do something with attachment.id and/or attachment.url here
      $('#image-preview').attr('src', attachment.url).css('width', 'auto');
      $('#image_attachment_id').val(attachment.id);

      // Restore the main post ID
      wp.media.model.settings.post.id = wp_media_post_id;
    });

    // Finally, open the modal
    file_frame.open();
  });

  // Restore the main ID when the add media button is pressed
  jQuery('a.add_media').on('click', function () {
    wp.media.model.settings.post.id = wp_media_post_id;
  });

  $('#xatkitLogo').change( function(e){
    if (document.getElementById("xatkitLogo").files.length == 0) {
     // Event to change file upload, unused!
    }
  });

  $('#xatColor').wpColorPicker();

  $('#excludeRoutes').select2({
    width: '350px',
    tags: true,
    placeholder: "  Write a piece of a route like: /myslug and hit enter",
    createTag: function (params) {
      if (params.term.length <= 3) {
        // Return null to disable tag creation
        return null;
      }
      return {
        id: params.term,
        text: params.term
      }
    },
  }).on("select2:select", function (e) {
    var op1 = document.createElement("option");
    op1.value = e.params.data.id;
    op1.text = e.params.data.text;
    var sel = document.getElementById("partialMin");
    sel.add(op1);
  }).on("select2:unselect", function (e) {
    var sel = document.getElementById("partialMin");
    for (var i = 0; i < sel.length; i++) {
      if (sel.options[i].value == e.params.data.id)
        sel.remove(i);
    }
  });
  $('#visibilityRoutes').select2({
    width: '350px',
    tags: true,
    placeholder: "  Write a piece of a route like: /myslug and hit enter",
    createTag: function (params) {
      if (params.term.length <= 3) {
          // Return null to disable tag creation
          return null;
      }
      return {
        id: params.term,
        text: params.term
      }
      },
  }).on("select2:select", function (e) {
     var op1 = document.createElement("option");
     op1.value = e.params.data.id;
     op1.text =  e.params.data.text;
     var sel = document.getElementById("partialMin");
     sel.add(op1);
  }).on("select2:unselect", function (e) {
    var sel = document.getElementById("partialMin");
    for (var i = 0; i < sel.length; i++) {
      if (sel.options[i].value == e.params.data.id)
        sel.remove(i);
    }
  });
  $('#partialMin').select2({
    width: '350px',
    tags: true,
    placeholder: "  Write a piece of a route like: /myslug and hit enter",
    createTag: function (params) {
      if (params.term.length <= 3) {
        // Return null to disable tag creation
        return null;
      }
      return {
        id: params.term,
        text: params.term
      }
    },
   });

  $("#alwaysmin").click(function () {
    if ($(this).is(":checked")) {
      document.getElementById("partialMin").disabled = true;
    } else {
      document.getElementById("partialMin").disabled = false;
    }
  });

  $("#all").click(function () {
    if ($(this).is(":checked")) {
      $("#visfilter").hide();
      $("#visexclude").show();
      $('#minim').show();
    }
  });
  $("#filters").click(function () {
    if ($(this).is(":checked")) {
      $("#visfilter").show();
      $("#visexclude").hide();
      $('#minim').hide();
    }
  });

  if($("#all").is(":checked")) {
    $("#visfilter").hide();
    $("#visexclude").show();
    $('#minim').show();
  } else {
    $("#visfilter").show();
    $("#visexclude").hide();
    $('#minim').hide();
  }

});

