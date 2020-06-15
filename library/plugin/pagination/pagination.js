;(function ($) {
  var js = document.scripts;
  var path = js[js.length - 1].src.substring(0, js[js.length - 1].src.lastIndexOf("/") + 1);
  var domain = "http://" + document.domain;
  var realPath = path.replace(domain, "");
  var defaults = { boxId: "#pagination", pages: 10, displayCounts: 5, currentIndex: 1, bHomePage: false, headText: "head", tailText: "tail", callback: "" };
  var methods = {
    init: function (options) {
      // call init();
      var settings = $.extend({}, defaults, options);
      settings.filePath = realPath;

      return $(this).each(function () {

        // load css file
        bCssFile = $("head").find("link[href='" + settings.filePath + "pagination.css']").length;
        if (!bCssFile) {
          $("head").append('<link rel="stylesheet" href="' + settings.filePath + 'pagination.css">');
        }

        $(this).attr("id", settings.boxId.slice(1));
        $(settings.boxId).append('<div class="pagination-content"></div><div class="pagination-buttons"></div>');
        if(settings.pages>1) {
          $(this).find(".pagination-buttons").append('<span class="btn btn-pagination btn-prev"><</span><span class="btn btn-pagination btn-next">></span>');
          for(var i=0; i<settings.displayCounts; i++) {
            $(this).find(".btn-next").before('<span class="btn btn-pagination btn-index">'+(i+1)+'</span>');
          }
          $(this).find(".btn-index").eq(settings.currentIndex-1).addClass("active");
        }
        else {
          console.log(0);
        }

        $(this).find(".btn-pagination").each(function () {
          $(this).off("click").on("click", function() {
            if(!$(this).hasClass("active")) {
              console.log($(this).index());
              if($(this).index() === 0) {}
            }
          });
        });

        // for(var i=0; i<settings.displayCounts; i++) {
        //   $(this).find()
        // }

        // $(this).find(".btn-pagination").each(function() {
        //   $(this).off("click").on("click", function() {
        //     console.log($(this).index());
        //   });
        // });

      });
    }

  };

  $.fn.pagination = function (method) {
    // 方法调用
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    }
    else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    }
    else {
      $.error('Method ' + method + ' does not exist on jQuery.pagination');
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

})(jQuery);