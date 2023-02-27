!function(e,n,a){"use strict";a.app=a.app||{};var i=a("body"),o=a(e),t=a('div[data-menu="menu-wrapper"]').html(),s=a('div[data-menu="menu-wrapper"]').attr("class");a.app.menu={expanded:null,collapsed:null,hidden:null,container:null,horizontalMenu:!1,manualScroller:{obj:null,init:function(){a(".main-menu").hasClass("menu-dark");this.obj=new PerfectScrollbar(".main-menu-content",{suppressScrollX:!0,wheelPropagation:!1})},update:function(){if(this.obj){if(!0===a(".main-menu").data("scroll-to-active")){var e,o,t;if(e=n.querySelector(".main-menu-content li.active"),i.hasClass("menu-collapsed"))a(".main-menu-content li.sidebar-group-active").length&&(e=n.querySelector(".main-menu-content li.sidebar-group-active"));else if(o=n.querySelector(".main-menu-content"),e&&(t=e.getBoundingClientRect().top+o.scrollTop),t>parseInt(2*o.clientHeight/3))var s=t-o.scrollTop-parseInt(o.clientHeight/2);setTimeout((function(){a.app.menu.container.stop().animate({scrollTop:s},300),a(".main-menu").data("scroll-to-active","false")}),300)}this.obj.update()}},enable:function(){a(".main-menu-content").hasClass("ps")||this.init()},disable:function(){this.obj&&this.obj.destroy()},updateHeight:function(){"vertical-menu"!=i.data("menu")&&"vertical-menu-modern"!=i.data("menu")&&"vertical-overlay-menu"!=i.data("menu")||!a(".main-menu").hasClass("menu-fixed")||(a(".main-menu-content").css("height",a(e).height()-a(".header-navbar").height()-a(".main-menu-header").outerHeight()-a(".main-menu-footer").outerHeight()),this.update())}},init:function(e){if(a(".main-menu-content").length>0){this.container=a(".main-menu-content");var n="";if(!0===e&&(n="collapsed"),"vertical-menu-modern"==i.data("menu")){this.change(n)}else this.change(n)}},drillDownMenu:function(e){a(".drilldown-menu").length&&("sm"==e||"xs"==e?"true"==a("#navbar-mobile").attr("aria-expanded")&&a(".drilldown-menu").slidingMenu({backLabel:!0}):a(".drilldown-menu").slidingMenu({backLabel:!0}))},change:function(n,o){var t=Unison.fetch.now();this.reset();var s,l,r=i.data("menu");if(t)switch(t.name){case"xl":"vertical-overlay-menu"===r?this.hide():"collapsed"===n?this.collapse(n):this.expand();break;case"lg":"vertical-overlay-menu"===r||"vertical-menu-modern"===r||"horizontal-menu"===r?this.hide():this.collapse();break;case"md":case"sm":case"xs":this.hide()}"vertical-menu"!==r&&"vertical-menu-modern"!==r||this.toOverlayMenu(t.name,r),i.is(".horizontal-layout")&&!i.hasClass(".horizontal-menu-demo")&&(this.changeMenu(t.name),a(".menu-toggle").removeClass("is-active")),"horizontal-menu"!=r&&this.drillDownMenu(t.name),"xl"==t.name&&(a('body[data-open="hover"] .dropdown').on("mouseenter",(function(){a(this).hasClass("show")?a(this).removeClass("show"):a(this).addClass("show")})).on("mouseleave",(function(e){a(this).removeClass("show")})),a('body[data-open="hover"] .dropdown a').on("click",(function(e){if("horizontal-menu"==r&&a(this).hasClass("dropdown-toggle"))return!1}))),a(".header-navbar").hasClass("navbar-brand-center")&&a(".header-navbar").attr("data-nav","brand-center"),"sm"==t.name||"xs"==t.name?a(".header-navbar[data-nav=brand-center]").removeClass("navbar-brand-center"):a(".header-navbar[data-nav=brand-center]").addClass("navbar-brand-center"),a("ul.dropdown-menu [data-toggle=dropdown]").on("click",(function(e){a(this).siblings("ul.dropdown-menu").length>0&&e.preventDefault(),e.stopPropagation(),a(this).parent().siblings().removeClass("show"),a(this).parent().toggleClass("show")})),"horizontal-menu"==r&&a("li.dropdown-submenu").on("mouseenter",(function(){a(this).parent(".dropdown").hasClass("show")||a(this).removeClass("openLeft");var n=a(this).find(".dropdown-menu");if(n){var i=a(e).height(),o=a(this).position().top,t=n.offset().left,s=n.width();if(i-o-n.height()-28<1){var l=i-o-170;a(this).find(".dropdown-menu").css({"max-height":l+"px","overflow-y":"auto","overflow-x":"hidden"});new PerfectScrollbar("li.dropdown-submenu.show .dropdown-menu",{wheelPropagation:!1})}t+s-(e.innerWidth-16)>=0&&a(this).addClass("openLeft")}})),"vertical-menu"!==r&&"vertical-overlay-menu"!==r||(jQuery.expr[":"].Contains=function(e,n,a){return(e.textContent||e.innerText||"").toUpperCase().indexOf(a[3].toUpperCase())>=0},s=a("#main-menu-navigation"),l=a(".menu-search"),a(l).change((function(){var e=a(this).val();if(e){a(".navigation-header").hide(),a(s).find("li a:not(:Contains("+e+"))").hide().parent().hide();var n=a(s).find("li a:Contains("+e+")");n.parent().hasClass("has-sub")?(n.show().parents("li").show().addClass("open").closest("li").children("a").show().children("li").show(),n.siblings("ul").length>0&&n.siblings("ul").children("li").show().children("a").show()):n.show().parents("li").show().addClass("open").closest("li").children("a").show()}else a(".navigation-header").show(),a(s).find("li a").show().parent().show().removeClass("open");return a.app.menu.manualScroller.update(),!1})).keyup((function(){a(this).change()})))},transit:function(e,n){var o=this;i.addClass("changing-menu"),e.call(o),i.hasClass("vertical-layout")&&(i.hasClass("menu-open")||i.hasClass("menu-expanded")?(a(".menu-toggle").addClass("is-active"),"vertical-menu"===i.data("menu")&&a(".main-menu-header")&&a(".main-menu-header").show()):(a(".menu-toggle").removeClass("is-active"),"vertical-menu"===i.data("menu")&&a(".main-menu-header")&&a(".main-menu-header").hide())),setTimeout((function(){n.call(o),i.removeClass("changing-menu"),o.update()}),500)},open:function(){this.transit((function(){i.removeClass("menu-hide menu-collapsed").addClass("menu-open"),this.hidden=!1,this.expanded=!0,i.hasClass("vertical-overlay-menu")&&(a(".sidenav-overlay").removeClass("d-none").addClass("d-block"),a("body").css("overflow","hidden"))}),(function(){!a(".main-menu").hasClass("menu-native-scroll")&&a(".main-menu").hasClass("menu-fixed")&&(this.manualScroller.enable(),a(".main-menu-content").css("height",a(e).height()-a(".header-navbar").height()-a(".main-menu-header").outerHeight()-a(".main-menu-footer").outerHeight())),i.hasClass("vertical-overlay-menu")||(a(".sidenav-overlay").removeClass("d-block d-none"),a("body").css("overflow","auto"))}))},hide:function(){this.transit((function(){i.removeClass("menu-open menu-expanded").addClass("menu-hide"),this.hidden=!0,this.expanded=!1,i.hasClass("vertical-overlay-menu")&&(a(".sidenav-overlay").removeClass("d-block").addClass("d-none"),a("body").css("overflow","auto"))}),(function(){!a(".main-menu").hasClass("menu-native-scroll")&&a(".main-menu").hasClass("menu-fixed")&&this.manualScroller.enable(),i.hasClass("vertical-overlay-menu")||(a(".sidenav-overlay").removeClass("d-block d-none"),a("body").css("overflow","auto"))}))},expand:function(){!1===this.expanded&&("vertical-menu-modern"==i.data("menu")&&a(".modern-nav-toggle").find(".toggle-icon").removeClass("bx bx-circle").addClass("bx bx-disc"),this.transit((function(){i.removeClass("menu-collapsed").addClass("menu-expanded"),this.collapsed=!1,this.expanded=!0,a(".sidenav-overlay").removeClass("d-block d-none")}),(function(){a(".main-menu").hasClass("menu-native-scroll")||"horizontal-menu"==i.data("menu")?this.manualScroller.disable():a(".main-menu").hasClass("menu-fixed")&&this.manualScroller.enable(),"vertical-menu"!=i.data("menu")&&"vertical-menu-modern"!=i.data("menu")||!a(".main-menu").hasClass("menu-fixed")||a(".main-menu-content").css("height",a(e).height()-a(".header-navbar").height()-a(".main-menu-header").outerHeight()-a(".main-menu-footer").outerHeight())})))},collapse:function(n){!1===this.collapsed&&("vertical-menu-modern"==i.data("menu")&&a(".modern-nav-toggle").find(".toggle-icon").removeClass("bx bx-disc").addClass("bx bx-circle"),this.transit((function(){i.removeClass("menu-expanded").addClass("menu-collapsed"),this.collapsed=!0,this.expanded=!1,a(".content-overlay").removeClass("d-block d-none")}),(function(){"horizontal-menu"==i.data("menu")&&i.hasClass("vertical-overlay-menu")&&a(".main-menu").hasClass("menu-fixed")&&this.manualScroller.enable(),"vertical-menu"!=i.data("menu")&&"vertical-menu-modern"!=i.data("menu")||!a(".main-menu").hasClass("menu-fixed")||a(".main-menu-content").css("height",a(e).height()-a(".header-navbar").height()),"vertical-menu-modern"==i.data("menu")&&a(".main-menu").hasClass("menu-fixed")&&this.manualScroller.enable()})))},toOverlayMenu:function(e,n){var a=i.data("menu");"vertical-menu-modern"==n?"lg"==e||"md"==e||"sm"==e||"xs"==e?i.hasClass(a)&&i.removeClass(a).addClass("vertical-overlay-menu"):i.hasClass("vertical-overlay-menu")&&i.removeClass("vertical-overlay-menu").addClass(a):"sm"==e||"xs"==e?i.hasClass(a)&&i.removeClass(a).addClass("vertical-overlay-menu"):i.hasClass("vertical-overlay-menu")&&i.removeClass("vertical-overlay-menu").addClass(a)},changeMenu:function(e){a('div[data-menu="menu-wrapper"]').html(""),a('div[data-menu="menu-wrapper"]').html(t),a(".menu-livicon").removeLiviconEvo(),a.each(a(".menu-livicon"),(function(e){var n=a(this),i=n.data("icon"),o=a("#main-menu-navigation").data("icon-style");n.addLiviconEvo({name:i,style:o,duration:.85,strokeWidth:"1.3px",eventOn:"parent",strokeColor:menuIconColorsObj.iconStrokeColor,solidColor:menuIconColorsObj.iconSolidColor,fillColor:menuIconColorsObj.iconFillColor,strokeColorAlt:menuIconColorsObj.iconStrokeColorAlt,afterAdd:function(){e===a(".main-menu-content .menu-livicon").length-1&&a(".main-menu-content .nav-item a").on("mouseenter",(function(){a(".main-menu-content .menu-livicon").length&&(a(".main-menu-content .menu-livicon").stopLiviconEvo(),a(this).find(".menu-livicon").playLiviconEvo())}))}})}));var n=a('div[data-menu="menu-wrapper"]'),o=(a('div[data-menu="menu-container"]'),a('ul[data-menu="menu-navigation"]')),l=a('li[data-menu="dropdown"]'),r=a('li[data-menu="dropdown-submenu"]');function d(e){e.updateLiviconEvo({strokeColor:menuActiveIconColorsObj.iconStrokeColor,solidColor:menuActiveIconColorsObj.iconSolidColor,fillColor:menuActiveIconColorsObj.iconFillColor,strokeColorAlt:menuActiveIconColorsObj.iconStrokeColorAlt})}"xl"===e?(i.removeClass("vertical-layout vertical-overlay-menu fixed-navbar").addClass(i.data("menu")),a("nav.header-navbar").removeClass("fixed-top"),n.removeClass().addClass(s),this.drillDownMenu(e),a("a.dropdown-item.nav-has-children").on("click",(function(){event.preventDefault(),event.stopPropagation()})),a("a.dropdown-item.nav-has-parent").on("click",(function(){event.preventDefault(),event.stopPropagation()}))):(i.removeClass(i.data("menu")).addClass("vertical-layout vertical-overlay-menu fixed-navbar"),a("nav.header-navbar").addClass("fixed-top"),n.removeClass().addClass("main-menu menu-fixed menu-shadow"),"dark-layout"===i.data("layout")||"semi-dark-layout"===i.data("layout")?n.addClass("menu-dark"):n.addClass("menu-light"),o.removeClass().addClass("navigation navigation-main"),l.removeClass("dropdown").addClass("has-sub"),l.find("a").removeClass("dropdown-toggle nav-link"),l.children("ul").find("a").removeClass("dropdown-item"),l.find("ul").removeClass("dropdown-menu"),r.removeClass().addClass("has-sub"),a.app.nav.init(),a("ul.dropdown-menu [data-toggle=dropdown]").on("click",(function(e){e.preventDefault(),e.stopPropagation(),a(this).parent().siblings().removeClass("open"),a(this).parent().toggleClass("open")}))),a(".main-menu-content").find("li.active").parents("li").addClass("sidebar-group-active"),a(".nav-item.active .menu-livicon").length&&d(a(".nav-item.active .menu-livicon")),a(".main-menu-content li.sidebar-group-active .menu-livicon").length&&d(a(".main-menu-content li.sidebar-group-active .menu-livicon"))},toggle:function(){var e=Unison.fetch.now(),n=(this.collapsed,this.expanded),a=this.hidden,o=i.data("menu");switch(e.name){case"xl":!0===n?"vertical-overlay-menu"==o?this.hide():this.collapse():"vertical-overlay-menu"==o?this.open():this.expand();break;case"lg":!0===n?"vertical-overlay-menu"==o||"vertical-menu-modern"==o||"horizontal-menu"==o?this.hide():this.collapse():"vertical-overlay-menu"==o||"vertical-menu-modern"==o||"horizontal-menu"==o?this.open():this.expand();break;case"md":case"sm":case"xs":!0===a?this.open():this.hide()}this.drillDownMenu(e.name)},update:function(){this.manualScroller.update()},reset:function(){this.expanded=!1,this.collapsed=!1,this.hidden=!1,i.removeClass("menu-hide menu-open menu-collapsed menu-expanded")}},a.app.nav={container:a(".navigation-main"),initialized:!1,navItem:a(".navigation-main").find("li").not(".navigation-category"),config:{speed:300},init:function(e){this.initialized=!0,a.extend(this.config,e),this.bind_events()},bind_events:function(){var e=this;a(".navigation-main").on("mouseenter.app.menu","li",(function(){var n=a(this);if(a(".hover",".navigation-main").removeClass("hover"),i.hasClass("menu-collapsed")&&"vertical-menu-modern"!=i.data("menu")){a(".main-menu-content").children("span.menu-title").remove(),a(".main-menu-content").children("a.menu-title").remove(),a(".main-menu-content").children("ul.menu-content").remove();var o,t,s,l=n.find("span.menu-title").clone();if(n.hasClass("has-sub")||(o=n.find("span.menu-title").text(),t=n.children("a").attr("href"),""!==o&&((l=a("<a>")).attr("href",t),l.attr("title",o),l.text(o),l.addClass("menu-title"))),s=n.css("border-top")?n.position().top+parseInt(n.css("border-top"),10):n.position().top,"vertical-compact-menu"!==i.data("menu")&&l.appendTo(".main-menu-content").css({position:"fixed",top:s}),n.hasClass("has-sub")&&n.hasClass("nav-item")){n.children("ul:first");e.adjustSubmenu(n)}}n.addClass("hover")})).on("mouseleave.app.menu","li",(function(){})).on("active.app.menu","li",(function(e){a(this).addClass("active"),e.stopPropagation()})).on("deactive.app.menu","li.active",(function(e){a(this).removeClass("active"),e.stopPropagation()})).on("open.app.menu","li",(function(n){var i=a(this);if(i.addClass("open"),e.expand(i),a(".main-menu").hasClass("menu-collapsible"))return!1;i.siblings(".open").find("li.open").trigger("close.app.menu"),i.siblings(".open").trigger("close.app.menu"),n.stopPropagation()})).on("close.app.menu","li.open",(function(n){var i=a(this);i.removeClass("open"),e.collapse(i),n.stopPropagation()})).on("click.app.menu","li",(function(e){var n=a(this);n.is(".disabled")||i.hasClass("menu-collapsed")&&"vertical-menu-modern"!=i.data("menu")?e.preventDefault():n.has("ul")?n.is(".open")?n.trigger("close.app.menu"):n.trigger("open.app.menu"):n.is(".active")||(n.siblings(".active").trigger("deactive.app.menu"),n.trigger("active.app.menu")),e.stopPropagation()})),a(".navbar-header, .main-menu").on("mouseenter",(function(){if("vertical-menu-modern"==i.data("menu")&&(a(".main-menu, .navbar-header").addClass("expanded"),i.hasClass("menu-collapsed"))){0===a(".main-menu li.open").length&&a(".main-menu-content").find("li.active").parents("li").addClass("open");var e=a(".main-menu li.menu-collapsed-open");e.children("ul").hide().slideDown(200,(function(){a(this).css("display","")})),e.addClass("open").removeClass("menu-collapsed-open")}})).on("mouseleave",(function(){i.hasClass("menu-collapsed")&&"vertical-menu-modern"==i.data("menu")&&setTimeout((function(){if(0===a(".main-menu:hover").length&&0===a(".navbar-header:hover").length&&(a(".main-menu, .navbar-header").removeClass("expanded"),i.hasClass("menu-collapsed"))){var e=a(".main-menu li.open"),n=e.children("ul");e.addClass("menu-collapsed-open"),n.show().slideUp(200,(function(){a(this).css("display","")})),e.removeClass("open")}}),1)})),a(".main-menu-content").on("mouseleave",(function(){i.hasClass("menu-collapsed")&&(a(".main-menu-content").children("span.menu-title").remove(),a(".main-menu-content").children("a.menu-title").remove(),a(".main-menu-content").children("ul.menu-content").remove()),a(".hover",".navigation-main").removeClass("hover")})),a(".navigation-main li.has-sub > a").on("click",(function(e){e.preventDefault()})),a("ul.menu-content").on("click","li",(function(n){var i=a(this);if(i.is(".disabled"))n.preventDefault();else if(i.has("ul"))if(i.is(".open"))i.removeClass("open"),e.collapse(i);else{if(i.addClass("open"),e.expand(i),a(".main-menu").hasClass("menu-collapsible"))return!1;i.siblings(".open").find("li.open").trigger("close.app.menu"),i.siblings(".open").trigger("close.app.menu"),n.stopPropagation()}else i.is(".active")||(i.siblings(".active").trigger("deactive.app.menu"),i.trigger("active.app.menu"));n.stopPropagation()}))},adjustSubmenu:function(e){var n,i,t,s,l,r=e.children("ul:first"),d=r.clone(!0);a(".main-menu-header").height(),n=e.position().top,t=o.height()-a(".header-navbar").height(),l=0,r.height(),parseInt(e.css("border-top"),10)>0&&(l=parseInt(e.css("border-top"),10)),s=t-n-e.height()-30,a(".main-menu").hasClass("menu-dark"),i=n+e.height()+l,d.addClass("menu-popout").appendTo(".main-menu-content").css({top:i,position:"fixed","max-height":s});new PerfectScrollbar(".main-menu-content > ul.menu-content",{wheelPropagation:!1})},collapse:function(e,n){e.children("ul").show().slideUp(a.app.nav.config.speed,(function(){a(this).css("display",""),a(this).find("> li").removeClass("is-shown"),n&&n(),a.app.nav.container.trigger("collapsed.app.menu")}))},expand:function(e,n){var i=e.children("ul"),o=i.children("li").addClass("is-hidden");i.hide().slideDown(a.app.nav.config.speed,(function(){a(this).css("display",""),n&&n(),a.app.nav.container.trigger("expanded.app.menu")})),setTimeout((function(){o.addClass("is-shown"),o.removeClass("is-hidden")}),0)},refresh:function(){a.app.nav.container.find(".open").removeClass("open")}}}(window,document,jQuery);
