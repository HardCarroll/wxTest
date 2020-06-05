;(function ($) {
  var js = document.scripts;
  var path = js[js.length - 1].src.substring(0, js[js.length - 1].src.lastIndexOf("/") + 1);
  var domain = "http://" + document.domain;
  var realPath = path.replace(domain, "");
  var defaults = { inputId: "#fileUpload", modalId: "#uploadModal", uploadOnSelect: false, callback: "" };
  var methods = {
    init: function (options) {
      // call init();
      var settings = $.extend({}, defaults, options);
      settings.filePath = realPath;

      return $(this).each(function () {
        // save DOM nodes
        settings.files = [];

        // load css file
        bCssFile = $("head").find("link[href='" + settings.filePath + "fileupload.css']").length;
        if (!bCssFile) {
          $("head").append('<link rel="stylesheet" href="' + settings.filePath + 'fileupload.css">');
        }

        // add input control to body if there is no input of inputId;
        var input = '<input type="file" id="' + settings.inputId.slice(1) + '" class="hidden" multiple>';
        if (!$("body").find(settings.inputId).length) {
          $("script").eq(0).before(input);
        }

        // add modal control to body if there is no modal of modalId;
        var modal = '<div class="modal" id="' + settings.modalId.slice(1) + '" tabindex="-1" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header">文件上传</div><div class="modal-body"></div><div class="modal-footer"><span class="tips">总共*个文件，*个已成功上传，*个上传失败！</span><button type="button" class="btn btn-default" data-dismiss="modal">关闭</button><button type="button" class="btn btn-primary btn-upload">上传</button></div></div></div></div>';
        if (!$("body").find(settings.modalId).length) {
          $("script").eq(0).before(modal);
          $(settings.modalId).on('hidden.bs.modal', function () {
            // empty all elements in .modal-body
            settings.files = [];
            $(this).find(".modal-body").empty();
          }).find(".btn-upload").off("click").on("click", function () {
            // starting upload files
            if (settings.files.length) {
              for (var i = 0; i < settings.files.length; i++) {
                uploadFiles(settings, i);
              }
            }
            else {
              console.log("you didn't select any file!");
            }
          });
        }

        $(this).addClass("glyphicon glyphicon-file").css({"cursor": "pointer"}).off("click").on("click", function () {
          // select any file you want to upload
          selectFiles(settings);
        });

      });
    }

  };

  $.fn.fileUpload = function (method) {
    // 方法调用
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    }
    else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    }
    else {
      $.error('Method ' + method + ' does not exist on jQuery.fileUpload');
    }
  };

  function createObjectURL(file) {
    if (window.webkitURL) {
      return window.webkitURL.createObjectURL(file);
    }
    else if (window.URL && window.URL.createObjectURL) {
      return window.URL.createObjectURL(file);
    }
    else {
      return null;
    }
  }

  function selectFiles(settings) {
    $(settings.inputId).click().off("change").on("change", function(e) {
      var box = $(settings.modalId).find(".modal-body");
      var ele = $('<div class="list"></div>');
      var files = settings.files = Array.prototype.slice.call(e.target.files);
      for(var i=0; i<files.length; i++) {
        var item = $('<div class="list-item"><div class="controls"></span><span class="glyphicon glyphicon-trash"></span></div><div class="progress hidden"><div class="progress-bar progress-bar-success"></div></div><div class="info"><span class="name"></span></div></div>');
        item.appendTo(ele).css({"background-image": "url("+createObjectURL(files[i])+")"}).find("span.name").html(files[i].name);
      }
      box.empty().append(ele);

      ele.find("span.glyphicon-trash").off("click").on("click", function() {
        // change array of files, delete current index of item
        // if delete all files, then make files null
        files.splice($(this).parent().parent().index(), 1);
        if(files.length) {
          // remove div.list-item
          $(this).parent().parent().remove();
        }
        else {
          // remove div.list
          $(this).parent().parent().parent().remove();
          $(settings.modalId).modal("hide");
        }

        settings.files = files;
      });

      if(settings.files.length) {
        if (settings.uploadOnSelect) {
          for (var i = 0; i < settings.files.length; i++) {
            uploadFiles(settings, i);
          }
        }
        else {
          // open modal of modalId
          $(settings.modalId).modal({
            backdrop: 'static',
            keyboard: false
          });
        }
      }
      
      // 解决选择相同文件不触发change事件
      e.target.value = null;

    });
  }

  function uploadFiles(settings, index = 0) {
    var pNode = $(settings.modalId).find(".list-item").eq(index);
    pNode.find(".progress").removeClass("hidden");
    pNode.find("span.name").html("");

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
            pNode.find(".progress-bar").css("width", percent + "%");
          }, false);
        }
        return myxhr;
      },
      success: function (ret) {
        // console.log(ret);
        if(ret.err_code) {
          pNode.find(".progress-bar").toggleClass("progress-bar-success").toggleClass("progress-bar-danger");
        }
        pNode.find("span.name").html(ret.err_msg);
      },
      error: function (err) {
        console.log(err);
      }
    });

  }

})(jQuery);