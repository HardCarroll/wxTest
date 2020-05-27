// $(function() {
//   $("#fileupload").click(function() {
//     $("#upload").click();
//   });
//   $("#upload").off("change").on("change", function(e) {
//     var fmd = new FormData();
//     for (var i=0; i<e.target.files.length; i++) {
//       var ele = $('<li class="list-item"><span class="file-name"></span><span class="seperator"> / </span><span class="file-size"></span><span class="progress"></span></li>');
//       ele.find(".file-name").html(e.target.files[i].name);
//       ele.find(".file-size").html(Math.floor(e.target.files[i].size/1024) + "Kb");
//       $(".file-list").append(ele);
//       fmd.append("files["+i+"]", e.target.files[i]);
//     }

//     $.ajax({
//       url: "/include/plugin/fileupload/fileupload.php",
//       type: "POST",
//       data: fmd,
//       processData: false,
//       contentType: false,
//       dataType: "json",
//       xhr: function() {
//         myxhr = $.ajaxSettings.xhr();
//         if(myxhr.upload) {
//           myxhr.upload.addEventListener("progress", function(e) {
//             var loaded = e.loaded;
//             var total = e.total;
//             var percent = Math.floor(loaded/total*100);
//             $(".progress").css("width",percent+"%");
//           }, false);
//         }
//         return myxhr;
//       },
//       success: function(ret) {
//         console.log(ret);
//       },
//       error: function(err) {
//         console.log(err);
//       }
//     });

//   });
// });
;(function($){
  var defaults = {cssFile: "/library/plugin/fileupload/fileupload.css", debug: false, callback: ""};
  var methods = {
    init: function(options) {
      // call init();
      var settings = $.extend({}, defaults, options);

      // load css file
      var bCssFile = $("head").find("link[href='"+ defaults.cssFile +"']").length;
      if(!bCssFile) {
        $("head").append('<link rel="stylesheet" href="'+defaults.cssFile+'">');
      }
      bCssFile = $("head").find("link[href='"+ settings.cssFile +"']").length;
      if(!bCssFile) {
        $("head").append('<link rel="stylesheet" href="'+settings.cssFile+'">');
      }

      return $(this).each(function() {
        $(this).off("change").on("change", function(e) {
          $(this).parent().find(".list").remove();
          var ele = $('<div class="list"></div>');
          var files = e.target.files;
          for(var i=0; i<files.length; i++) {
            var item = $('<div class="list-item"></div>');
            item.css({"background-image": "url("+createObjectURL(files[i])+")"});
            item.append('<div class="controls"><span class="glyphicon glyphicon-unchecked"></span><span class="glyphicon glyphicon-trash"></span></div><div class="info"><span class="name"></span></div>').appendTo(ele);
            item.find("span.name").html(files[i].name);
            ele.appendTo($(this).parent())
          }

          ele.find("span.glyphicon-trash").off("click").on("click", function() {
            // console.log("trash");
            $(this).parent().parent().remove();
            // console.log(files);
          });
        });

        if(settings.callback) {
          settings.callback.apply(this);
        }
      });
    },
    sayHi: function(options) {
      return $(this).each(function() {
        var files = $(this)[0].files;
        console.log(files);
      });
    }
  };

  $.fn.fileUpload = function(method) {
    // 方法调用
    if(methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    }
    else if(typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    }
    else {
      $.error('Method ' + method + ' does not exist on jQuery.fileUpload');
    }
  };

  function createObjectURL(file) {
    if(window.webkitURL) {
      return window.webkitURL.createObjectURL(file);
    }
    else if(window.URL && window.URL.createObjectURL) {
      return window.URL.createObjectURL(file);
    }
    else {
      return null;
    }
  }

  function debug(e) {
  }

})(jQuery);