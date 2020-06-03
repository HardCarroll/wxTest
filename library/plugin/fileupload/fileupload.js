;(function($){
  var js = document.scripts;
  var path = js[js.length-1].src.substring(0, js[js.length-1].src.lastIndexOf("/")+1);
  var domain = "http://" + document.domain;
  var realPath = path.replace(domain, "");
  var defaults = {filePath: realPath, inputId: "#fileUpload", debug: false, callback: ""};
  var methods = {
    init: function(options) {
      // call init();
      var settings = $.extend({}, defaults, options);

      return $(this).each(function() {
        // save DOM nodes
        settings.this = $(this);
        // settings.files = null;
        
        // load css file
        bCssFile = $("head").find("link[href='"+ settings.filePath +"fileupload.css']").length;
        if(!bCssFile) {
          $("head").append('<link rel="stylesheet" href="'+settings.filePath+'fileupload.css">');
        }

        // add buttons: select file & upload file
        settings.this.append('<div class="button"><div class="btn btn-default btn-select" data-target="'+settings.inputId+'">选择文件</div><div class="btn btn-success btn-upload" disabled>开始上传</div></div>');
        if(!$("body").find(settings.inputId).length) {
          $("body").append('<input type="file" id="'+settings.inputId.slice(1)+'" class="hidden" multiple>');
        }

        settings.this.find(".btn-select").off("click").on("click", function() {
          selectFiles(settings);
        });

        settings.this.find(".btn-upload").off("click").on("click", function() {
          if(settings.files) {
            for(var i=0; i<settings.files.length; i++) {
              uploadFiles(settings, i);
            }
          }
        });
      });
    },
    debug: function(msg) {
      console.log(msg);
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

  function selectFiles(settings) {
    $(settings.inputId).click().off("change").on("change", function(e) {
      settings.this.find(".list").remove();
      var ele = $('<div class="list"></div>');
      var files = settings.files = Array.prototype.slice.call(e.target.files);
      for(var i=0; i<files.length; i++) {
        var item = $('<div class="list-item"><div class="controls"></span><span class="glyphicon glyphicon-trash"></span></div><div class="progress hidden"><div class="progress-bar progress-bar-success"></div></div><div class="info"><span class="name"></span></div></div>');
        item.appendTo(ele).css({"background-image": "url("+createObjectURL(files[i])+")"}).find("span.name").html(files[i].name);
      }
      ele.appendTo(settings.this);

      ele.find("span.glyphicon-trash").off("click").on("click", function() {
        files.splice($(this).parent().parent().index(), 1);
        if(files.length) {
          // remove div.list-item
          $(this).parent().parent().remove();
        }
        else {
          // remove div.list
          $(this).parent().parent().parent().remove();
        }

        settings.files = files;
      });
    });
  }

  function uploadFiles(settings, index = 0) {
    // console.log(settings);
    settings.this.find(".progress").eq(index).removeClass("hidden");

    var fmd = new FormData();
    fmd.append("uploadFiles", settings.files[index]);
    fmd.append("token", "uploadFiles");
    $.ajax({
      url: settings.filePath + "fileupload.php",
      type: "POST",
      data: fmd,
      processData: false,
      contentType: false,
      dataType: "json",
      xhr: function () {
        myxhr = $.ajaxSettings.xhr();
        if (myxhr.upload) {
          myxhr.upload.addEventListener("progress", function (e) {
            var loaded = e.loaded;
            var total = e.total;
            var percent = Math.floor(loaded / total * 100);
            $(".progress-bar").eq(index).css("width", percent + "%");
          }, false);
        }
        return myxhr;
      },
      success: function (ret) {
        console.log(ret);
      },
      error: function (err) {
        console.log(err);
      }
    });

  }

})(jQuery);