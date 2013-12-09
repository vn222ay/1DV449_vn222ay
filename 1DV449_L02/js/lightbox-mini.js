(function(){var t,i,e;t=jQuery,e=function(){function t(){this.fadeDuration=500,this.fitImagesInViewport=!0,this.resizeDuration=700,this.showImageNumberLabel=!0,this.wrapAround=!1}return t.prototype.albumLabel=function(t,i){return"Image "+t+" of "+i},t}(),i=function(){function i(t){this.options=t,this.album=[],this.currentImageIndex=void 0,this.init()}return i.prototype.init=function(){return this.enable(),this.build()},i.prototype.enable=function(){var i=this;return t("body").on("click","a[rel^=lightbox], area[rel^=lightbox], a[data-lightbox], area[data-lightbox]",function(e){return i.start(t(e.currentTarget)),!1})},i.prototype.build=function(){var i=this;return t("<div id='lightboxOverlay' class='lightboxOverlay'></div><div id='lightbox' class='lightbox'><div class='lb-outerContainer'><div class='lb-container'><img class='lb-image' src='' /><div class='lb-nav'><a class='lb-prev' href='' ></a><a class='lb-next' href='' ></a></div><div class='lb-loader'><a class='lb-cancel'></a></div></div></div><div class='lb-dataContainer'><div class='lb-data'><div class='lb-details'><span class='lb-caption'></span><span class='lb-number'></span></div><div class='lb-closeContainer'><a class='lb-close'></a></div></div></div></div>").appendTo(t("body")),this.$lightbox=t("#lightbox"),this.$overlay=t("#lightboxOverlay"),this.$outerContainer=this.$lightbox.find(".lb-outerContainer"),this.$container=this.$lightbox.find(".lb-container"),this.containerTopPadding=parseInt(this.$container.css("padding-top"),10),this.containerRightPadding=parseInt(this.$container.css("padding-right"),10),this.containerBottomPadding=parseInt(this.$container.css("padding-bottom"),10),this.containerLeftPadding=parseInt(this.$container.css("padding-left"),10),this.$overlay.hide().on("click",function(){return i.end(),!1}),this.$lightbox.hide().on("click",function(e){return"lightbox"===t(e.target).attr("id")&&i.end(),!1}),this.$outerContainer.on("click",function(e){return"lightbox"===t(e.target).attr("id")&&i.end(),!1}),this.$lightbox.find(".lb-prev").on("click",function(){return 0===i.currentImageIndex?i.changeImage(i.album.length-1):i.changeImage(i.currentImageIndex-1),!1}),this.$lightbox.find(".lb-next").on("click",function(){return i.currentImageIndex===i.album.length-1?i.changeImage(0):i.changeImage(i.currentImageIndex+1),!1}),this.$lightbox.find(".lb-loader, .lb-close").on("click",function(){return i.end(),!1})},i.prototype.start=function(i){var e,n,a,o,r,h,s,l,d,g,c,b,u;if(t(window).on("resize",this.sizeOverlay),t("select, object, embed").css({visibility:"hidden"}),this.$overlay.width(t(document).width()).height(t(document).height()).fadeIn(this.options.fadeDuration),this.album=[],r=0,a=i.attr("data-lightbox"))for(b=t(i.prop("tagName")+'[data-lightbox="'+a+'"]'),o=l=0,g=b.length;g>l;o=++l)n=b[o],this.album.push({link:t(n).attr("href"),title:t(n).attr("title")}),t(n).attr("href")===i.attr("href")&&(r=o);else if("lightbox"===i.attr("rel"))this.album.push({link:i.attr("href"),title:i.attr("title")});else for(u=t(i.prop("tagName")+'[rel="'+i.attr("rel")+'"]'),o=d=0,c=u.length;c>d;o=++d)n=u[o],this.album.push({link:t(n).attr("href"),title:t(n).attr("title")}),t(n).attr("href")===i.attr("href")&&(r=o);e=t(window),s=e.scrollTop()+e.height()/10,h=e.scrollLeft(),this.$lightbox.css({top:s+"px",left:h+"px"}).fadeIn(this.options.fadeDuration),this.changeImage(r)},i.prototype.changeImage=function(i){var e,n,a=this;this.disableKeyboardNav(),e=this.$lightbox.find(".lb-image"),this.sizeOverlay(),this.$overlay.fadeIn(this.options.fadeDuration),t(".lb-loader").fadeIn("slow"),this.$lightbox.find(".lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption").hide(),this.$outerContainer.addClass("animating"),n=new Image,n.onload=function(){var o,r,h,s,l,d,g;return e.attr("src",a.album[i].link),o=t(n),e.width(n.width),e.height(n.height),a.options.fitImagesInViewport&&(g=t(window).width(),d=t(window).height(),l=g-a.containerLeftPadding-a.containerRightPadding-20,s=d-a.containerTopPadding-a.containerBottomPadding-110,(n.width>l||n.height>s)&&(n.width/l>n.height/s?(h=l,r=parseInt(n.height/(n.width/h),10),e.width(h),e.height(r)):(r=s,h=parseInt(n.width/(n.height/r),10),e.width(h),e.height(r)))),a.sizeContainer(e.width(),e.height())},n.src=this.album[i].link,this.currentImageIndex=i},i.prototype.sizeOverlay=function(){return t("#lightboxOverlay").width(t(document).width()).height(t(document).height())},i.prototype.sizeContainer=function(t,i){var e,n,a,o,r=this;o=this.$outerContainer.outerWidth(),a=this.$outerContainer.outerHeight(),n=t+this.containerLeftPadding+this.containerRightPadding,e=i+this.containerTopPadding+this.containerBottomPadding,this.$outerContainer.animate({width:n,height:e},this.options.resizeDuration,"swing"),setTimeout(function(){r.$lightbox.find(".lb-dataContainer").width(n),r.$lightbox.find(".lb-prevLink").height(e),r.$lightbox.find(".lb-nextLink").height(e),r.showImage()},this.options.resizeDuration)},i.prototype.showImage=function(){this.$lightbox.find(".lb-loader").hide(),this.$lightbox.find(".lb-image").fadeIn("slow"),this.updateNav(),this.updateDetails(),this.preloadNeighboringImages(),this.enableKeyboardNav()},i.prototype.updateNav=function(){this.$lightbox.find(".lb-nav").show(),this.album.length>1&&(this.options.wrapAround?this.$lightbox.find(".lb-prev, .lb-next").show():(this.currentImageIndex>0&&this.$lightbox.find(".lb-prev").show(),this.currentImageIndex<this.album.length-1&&this.$lightbox.find(".lb-next").show()))},i.prototype.updateDetails=function(){var t=this;"undefined"!=typeof this.album[this.currentImageIndex].title&&""!==this.album[this.currentImageIndex].title&&this.$lightbox.find(".lb-caption").html(this.album[this.currentImageIndex].title).fadeIn("fast"),this.album.length>1&&this.options.showImageNumberLabel?this.$lightbox.find(".lb-number").text(this.options.albumLabel(this.currentImageIndex+1,this.album.length)).fadeIn("fast"):this.$lightbox.find(".lb-number").hide(),this.$outerContainer.removeClass("animating"),this.$lightbox.find(".lb-dataContainer").fadeIn(this.resizeDuration,function(){return t.sizeOverlay()})},i.prototype.preloadNeighboringImages=function(){var t,i;this.album.length>this.currentImageIndex+1&&(t=new Image,t.src=this.album[this.currentImageIndex+1].link),this.currentImageIndex>0&&(i=new Image,i.src=this.album[this.currentImageIndex-1].link)},i.prototype.enableKeyboardNav=function(){t(document).on("keyup.keyboard",t.proxy(this.keyboardAction,this))},i.prototype.disableKeyboardNav=function(){t(document).off(".keyboard")},i.prototype.keyboardAction=function(t){var i,e,n,a,o;i=27,e=37,n=39,o=t.keyCode,a=String.fromCharCode(o).toLowerCase(),o===i||a.match(/x|o|c/)?this.end():"p"===a||o===e?0!==this.currentImageIndex&&this.changeImage(this.currentImageIndex-1):("n"===a||o===n)&&this.currentImageIndex!==this.album.length-1&&this.changeImage(this.currentImageIndex+1)},i.prototype.end=function(){return this.disableKeyboardNav(),t(window).off("resize",this.sizeOverlay),this.$lightbox.fadeOut(this.options.fadeDuration),this.$overlay.fadeOut(this.options.fadeDuration),t("select, object, embed").css({visibility:"visible"})},i}(),t(function(){var t,n;return n=new e,t=new i(n)})}).call(this);