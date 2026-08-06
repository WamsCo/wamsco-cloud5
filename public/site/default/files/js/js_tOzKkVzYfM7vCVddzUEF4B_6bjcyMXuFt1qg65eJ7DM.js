
function iOS() {
  var iDevices = [
    'iPad Simulator',
    'iPhone Simulator',
    'iPod Simulator',
    'iPad',
    'iPhone',
    'iPod'
  ];
  if (!!navigator.platform) {
    while (iDevices.length) {
      if (navigator.platform === iDevices.pop()){ return true; }
    }
  }
  return false;
}

if(iOS() === true) {
  jQuery(".zero-menu, .mob-site").addClass("disableform");
}
;


jQuery(document).ready(function () {

  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > 1 && document.documentElement.clientWidth < 1280 ) {
      jQuery(".headermain").addClass("fixik");
      jQuery(".content-section").not(".node-type-signup .content-section").addClass("margin54");
      if (document.documentElement.clientWidth > 600) {
        jQuery("#block-buyhardware").show()
        jQuery("#block-buyhardware-3").show()
        jQuery("#block-buyhardwaremain-2").show()
        jQuery("#block-buyhardwarebuttonjp").show()
      }
    }
    else if (jQuery(this).scrollTop() > 24 && jQuery(".headermain")) {
      jQuery(".headermain").addClass("fixik");
      jQuery(".content-section").not(".node-type-signup .content-section").addClass("margin54");
      if (document.documentElement.clientWidth > 600) {
        jQuery("#block-buyhardware").show()
        jQuery("#block-buyhardware-3").show()
        jQuery("#block-buyhardwaremain-2").show()
        jQuery("#block-buyhardwarebuttonjp").show()
      }
    }
    else {
      jQuery(".headermain").removeClass("fixik");
      jQuery(".content-section").not(".node-type-signup .content-section").removeClass("margin54");
      jQuery("#block-buyhardware").hide()
      jQuery("#block-buyhardware-3").hide()
      jQuery("#block-buyhardwaremain-2").hide()
      jQuery("#block-buyhardwarebuttonjp").hide()
    }
  });
});
;



/* listening to full loading of content pages */
window.addEventListener('load', function () {
  /* footerBlockAll array h3 */
  let footerBlockAll = document.querySelectorAll(".views-element-container .block-title");
  let col4 = document.querySelectorAll(".block-loyverse-footer #block-loyversefooter-menu")
  /* I loop through the array to find out which one I clicked on */
  for (let i = 0; i < footerBlockAll.length; i++) {
    /* for the required array I call the function footerBlockOpen */
    footerBlockAll[i].addEventListener("click", footerBlockOpen, false);
  }
  for (let i = 0; i < col4.length; i++) {
    col4[i].addEventListener("click", footerBlockOpen, false)
  }
  function footerBlockOpen() {
    /* I check for the presence of a class if it fugues then I kill it, and if not, then I add */
    if (this.parentElement.classList.contains('selected')) {
      this.parentElement.classList.remove('selected');
    }
    else {
      this.parentElement.classList.add("selected");
    }
  }
});
;
/*
 Create Ioan Sche by 11.04.2020
*/
/* for the future, starting from position block, correct as implemented on the main page */



/* here we check if scroll more then 640px by top, in block change position from relative to fixed */
window.addEventListener('load', function () {
  /* "so that the content stretches as much as possible up to 1296px, let's try to make the max width 1296 here too"
    now its width: 1312px; by 2020.06.01 */
  // let hardwaremenuleft = document.getElementById("block-hardwaremenuleft");
  let hard = document.getElementById("block-hardwarecontentposprintersothersinmenu")
  if (!hard) {
    /* if there is no element with id block-hardwarecontentposprintersothersinmenu, then this is not a Hardware page, so we stop executing the function */
    return;
  }

  // document.getElementsByClassName("content-textpad")[0].classList.add("hardware_block");
  /*  add by all menu items class "block-..."  */
  let hardwaremenuleft = document.querySelector('.content-textpad nav');
  let hardwareBanner = document.getElementById('block-bannerbuyhardware');
  let hardwareBannerUS = document.getElementById('block-bannerbuyhardwareus');
  let hardwareBannerJP = document.getElementById('block-bannerbuyhardwarejp');
  let hardwareMenuAllItems = hardwaremenuleft.querySelectorAll('.lmenu li');

  for (let i = 0; i < hardwareMenuAllItems.length; i++) {
    hardwareMenuAllItems[i].classList.add("block-" + (i + 1));
  }


  window.addEventListener('scroll', function () {
    onScrollAndResize();
    fixHardwareTabs()
  });

  jQuery(window).resize(function () {
    let windowWidth = jQuery(window).width();
    if(windowWidth > 1279) {
      onScrollAndResize();
    } else {
      let hwmenucont = document.getElementById("block-hardwarecontentposprintersothersinmenu");

      if (hardwaremenuleft.classList.contains("fixed")) {
        hardwaremenuleft.classList.remove("fixed");
      }

      if (hardwaremenuleft.classList.contains("bottom")) {
        hardwaremenuleft.classList.remove("bottom");
      }

      if (hwmenucont.classList.contains("fixed")) {
        hwmenucont.classList.remove("fixed");
      }
    }
  });

  /*  Hardware menu animation  */
  jQuery("#block-hardwaremenuleft .lmenu").on("click","a", function (event) {
    /* remove default link action */
    event.preventDefault();
    /* take the meaning of href attribute */
    let id = jQuery(this).attr('href'),
      /* height from the page top till anchor begins */
      top = jQuery(id).offset().top - 74;
    /* animation when going to anchor */
    jQuery('body,html').animate({scrollTop: top}, 1500);
  });

  jQuery("#block-hardwaremenuleftpl .lmenu").on("click","a", function (event) {
    event.preventDefault();
    let id = jQuery(this).attr('href'),
      top = jQuery(id).offset().top - 74;
    jQuery('body,html').animate({scrollTop: top}, 1500);
  });
  jQuery("#block-hardwaremenuleftit .lmenu").on("click","a", function (event) {
    event.preventDefault();
    let id = jQuery(this).attr('href'),
      top = jQuery(id).offset().top - 74;
    jQuery('body,html').animate({scrollTop: top}, 1500);
  });
  jQuery("#block-hardwaremenuleftjp .lmenu").on("click","a", function (event) {
    event.preventDefault();
    let id = jQuery(this).attr('href'),
      top = jQuery(id).offset().top - 74;
    jQuery('body,html').animate({scrollTop: top}, 1500);
  });
  jQuery("#block-hardwaremenuleftro .lmenu").on("click","a", function (event) {
    event.preventDefault();
    let id = jQuery(this).attr('href'),
      top = jQuery(id).offset().top - 74;
    jQuery('body,html').animate({scrollTop: top}, 1500);
  });
  jQuery("#block-hardwaremenuleftgr .lmenu").on("click","a", function (event) {
    event.preventDefault();
    let id = jQuery(this).attr('href'),
      top = jQuery(id).offset().top - 74;
    jQuery('body,html').animate({scrollTop: top}, 1500);
  });
  /* END Hardware menu animation */


  /* TOP Hardware FILTER BY TABS */
  jQuery(".hardware-tabs-links > a").click(function (e) {
    e.preventDefault();
    jQuery(this).siblings("a").removeClass("active");
    jQuery(this).addClass("active");
  });

  let hardware = jQuery(".features.hardware");
  jQuery("#android").click(function () {
    hardware.find(".features-hardware:hidden").fadeIn();
    hardware.find(".features-hardware.hardware-selected").removeClass("hardware-selected");
    jQuery(".hardware-info.android").parents(".features-hardware").addClass("hardware-selected");
    hardware.find(".features-hardware").not(".hardware-selected").fadeOut();
    jQuery(".featurestit.desktop").fadeOut();
    jQuery(".featurestit.fiskal").fadeOut();
    /* if andr checked */
    jQuery(".featurestit.hardware-info").fadeIn();
    jQuery("#block-hardwaremenuleft .block-6").fadeIn();

    jQuery("#block-hardwaremenuleftit .block-1").fadeOut();/* Imprimante fiscale */
    jQuery("#block-hardwaremenuleftit .block-7").fadeIn();
    jQuery("#block-hardwaremenuleftit .block-8").fadeOut();

    jQuery("#block-hardwaremenuleftpl .block-1").fadeOut();/* Stampanti fiscali */
    jQuery("#block-hardwaremenuleftpl .block-7").fadeIn();

    jQuery("#block-hardwaremenuleftro .block-1").fadeOut();/* Imprimante fiscale */
    jQuery("#block-hardwaremenuleftro .block-7").fadeIn();/* Terminale POS Android */

    jQuery("#block-hardwaremenuleftgr .block-6").fadeIn();

    jQuery("#block-hardwaremenuleftjp .block-1").fadeIn();
    jQuery("#block-hardwaremenuleftjp .block-8").fadeIn();

  });

  jQuery("#ios").click(function () {
    hardware.find(".features-hardware:hidden").fadeIn();
    hardware.find(".features-hardware.hardware-selected").removeClass("hardware-selected");
    jQuery(".hardware-info.apple").parents(".features-hardware").addClass("hardware-selected");
    hardware.find(".features-hardware").not(".hardware-selected").fadeOut();
    jQuery(".featurestit.desktop").fadeOut();
    jQuery(".featurestit.fiskal").fadeIn();
    /* if ios checked */
    jQuery(".featurestit.hardware-info").fadeOut();
    jQuery("#block-hardwaremenuleft .block-6").fadeOut();

    jQuery("#block-hardwaremenuleftit .block-1").fadeIn();/* Stampantifiscali */
    jQuery("#block-hardwaremenuleftit .block-7").fadeOut();/* TerminaAndroid */
    jQuery("#block-hardwaremenuleftit .block-8").fadeIn();/* Supporablet */

    jQuery("#block-hardwaremenuleftpl .block-1").fadeIn();/* Stampanti fiscali */
    jQuery("#block-hardwaremenuleftpl .block-7").fadeOut();/* Terminali di punto vendita Android */

    jQuery("#block-hardwaremenuleftro .block-1").fadeIn();/* Imprimante fiscale */
    jQuery("#block-hardwaremenuleftro .block-7").fadeOut();/* Terminale POS Android */

    jQuery("#block-hardwaremenuleftgr .block-6").fadeOut();

    jQuery("#block-hardwaremenuleftjp .block-1").fadeOut();
    jQuery("#block-hardwaremenuleftjp .block-8").fadeOut();

  });

  jQuery("#all").click(function () {
    hardware.find(".features-hardware:hidden").fadeIn();
    hardware.find(".features-hardware.hardware-selected").removeClass("hardware-selected");
    jQuery(".featurestit.desktop").fadeIn();
    jQuery(".featurestit.fiskal").fadeIn();
    /* if all checked */
    jQuery(".featurestit.hardware-info").fadeIn();
    jQuery("#block-hardwaremenuleft .block-6").fadeIn();
    jQuery("#block-hardwaremenuleftit .block-1").fadeIn();/* Stampanti fiscali */
    jQuery("#block-hardwaremenuleftit .block-7").fadeIn();
    jQuery("#block-hardwaremenuleftit .block-8").fadeIn();

    jQuery("#block-hardwaremenuleftpl .block-1").fadeIn();
    jQuery("#block-hardwaremenuleftpl .block-7").fadeIn();

    jQuery("#block-hardwaremenuleftro .block-1").fadeIn();
    jQuery("#block-hardwaremenuleftro .block-7").fadeIn();

    jQuery("#block-hardwaremenuleftgr .block-6").fadeIn();

    jQuery("#block-hardwaremenuleftjp .block-8").fadeIn();
    jQuery("#block-hardwaremenuleftjp .block-1").fadeIn();

  });
  /* END TOP Hardware FILTER BY TABS */


  function onScrollAndResize() {
    let hwmenucont = document.getElementById("block-hardwarecontentposprintersothersinmenu");

    if (hardwaremenuleft && jQuery(window).width() > 1279) {
      let blocks = [];

      for (let i = 1; i < hardwareMenuAllItems.length; i++) {
        blocks[i] = jQuery("#block-" + i);
        blocks[i] = jQuery(jQuery(blocks[i])).offset().top;
      }

      let scrollTop = jQuery(this).scrollTop();

      if (scrollTop + 54 > blocks[1]) {
        hardwaremenuleft.classList.add("fixed");
        hwmenucont.classList.add("fixed");
        hardwaremenuleft.classList.remove("bottom");
        // hardwareBanner.classList.add("fixed");
        // hardwareBanner.style.top = "460px";
      }
      else {/* When u back scroll in top position */
        hardwaremenuleft.classList.remove("fixed");
        hwmenucont.classList.remove("fixed");
        // hardwareBanner.classList.remove("fixed");
        // hardwareBanner.style.top = "760px";
      }


      /* I select the very last block that corresponds to the item in the menu on the left */
      blocks[hardwareMenuAllItems.length] = jQuery("#block-" + hardwareMenuAllItems.length)
      let topLastItem = blocks[hardwareMenuAllItems.length].offset().top
      let heightLastItem = blocks[hardwareMenuAllItems.length].height()
      let heightMenuItems = jQuery('.content-textpad nav .lmenu').height() + 72
      /* I calculate that the scroll would be no more than the height of the menu block */
      if (scrollTop + heightMenuItems > heightLastItem + topLastItem) {
        hardwaremenuleft.classList.add("bottom");
      }

      if (scrollTop + 148 > blocks[2]) {
        jQuery(".block-1").removeClass("active");
      }
      else {
        jQuery(".block-1").addClass("active");
      }


      for (let i = 2; i < hardwareMenuAllItems.length + 1; i++) {
        let scroll = jQuery(window).scrollTop();
        blocks[i] = jQuery("#block-" + i);
        let top = blocks[i].offset().top - 100;
        let end_block = top + blocks[i].height();
        if (scroll > top && scroll < end_block) {
          jQuery(".block-" + [i]).addClass("active");
        }
        else {
          jQuery(".block-" + [i]).removeClass("active");
        }
      }
    }
  }

  // let idTab = document.getElementById("tab");

  function fixHardwareTabs(elem) {
    let tabsBlock = document.getElementById("block-tabsallandroidios");
    let contentTextpad = document.querySelector(".content-textpad");
    let contentSection = document.querySelector(".content-section");
    // console.log("hardwareBlock.offsetTop  "+ hardwareBlock.offsetTop )
    let scrollTop = document.documentElement.scrollTop
    // console.log("scrollTop "+scrollTop)
    // console.log("hardwareBlock.offsetTop "+hardwareBlock.offsetTop)

    if (scrollTop > contentTextpad.offsetTop - 104) {
    // if (scrollTop > 213) {
      tabsBlock.classList.add("tabs-fix");
      // document.getElementsByClassName("content-textpad")[0].style.paddingTop = "104px";
      // document.querySelector(".content-section.margin54").style.marginTop = "104px";
      // jQuery(".content-section").addClass("margin104");
      contentSection.classList.add("margin104");
      // document.querySelector(".content-section").classList.add("margin104");

    }
    // else if (document.documentElement.clientWidth < 1280){
    //   tabsBlock.classList.remove("tabs-fix");
    //   document.querySelector(".content-section.margin54").style.marginTop = "54px";
    // }
    else {
      tabsBlock.classList.remove("tabs-fix");
      // document.querySelector(".content-section.margin54").style.marginTop = "54px";
      contentSection.classList.remove("margin104");
      // document.querySelector(".content-section").classList.remove("margin104");
      // jQuery(".content-section").removeClass("margin104");

    }
  }


});
;
/** POPUP COUNTRY HINT */
jQuery(".popup-link").hover(function() {
  jQuery(this).find(".popup-country-hint").slideToggle(200);
});
/** END POPUP COUNTRY HINT */
;
/*
 *  Create Ioan Sche by 11.04.2020
 */

/* add to lmenu li clacc block-1 2 3... */
window.addEventListener('load', function () {
  let all_li = document.querySelectorAll('#block-featuresmenuleft>ul>li');
  for (let i = 0; i < all_li.length; i++) {
    all_li[i].classList.add("block-" + (i + 1));
  }
  // jQuery(".block-1").addClass("active");
  let featuresmenuleft = jQuery("#block-featuresmenuleft");
  let featurescontentpointofsale = jQuery("#block-featurescontentpointofsale");

  jQuery(window).scroll(function () {
    onScrollAndResize(featuresmenuleft, featurescontentpointofsale);
  });

  /* resizing the window: if the width is less than the one at which the menu should appear on the left,
   then we remove from the blocks all classes that could have been added when scrolling;
   otherwise we use the same function as when scrolling */
  jQuery(window).resize(function () {
    let windowWidth = document.documentElement.clientWidth;
    if(windowWidth > 960) {
      onScrollAndResize(featuresmenuleft, featurescontentpointofsale);
    } else {
      let blocks = jQuery("#block-featuresmenuleft>ul>li");
      blocks.each(function () {
        let block = jQuery(this);
        if(block.hasClass("active")) {
          block.removeClass("active");
        }
      });
      if (featuresmenuleft.hasClass("fixed")) {
        featuresmenuleft.removeClass("fixed");
      }
      if (featuresmenuleft.hasClass("bottom")) {
        featuresmenuleft.removeClass("bottom");
      }
      if (featurescontentpointofsale.hasClass("fixed")) {
        featurescontentpointofsale.removeClass("fixed");
      }
    }
  });


  /* Hardware menu animation */
  jQuery("#block-featuresmenuleft .lmenu").on("click","a", function (event) {
    /*  Remove default link action  */
    event.preventDefault();
    /*  take the meaning of href attributes  */
    let id = jQuery(this).attr('href'),
      /*  height from the page top till anchor begins */
      top = jQuery(id).offset().top - 111;
    /* animation when going to anchor */
    jQuery('body,html').animate({scrollTop: top}, 1500);
  });


  /* so that the content stretches as much as possible up to 1296px, let's try to make the max width 1296 here too */
  // if (document.getElementById("block-featuresmenuleft")) {
  //   document.getElementsByClassName("content-textpad")[0].classList.add("features_block");
  // }

  /* function to stick menu and add active classes to it; used on scroll and resize events */
  function onScrollAndResize(featuresmenuleft, featurescontentpointofsale) {
    /* position block */
    if (featuresmenuleft.is(':visible')) {
      let blocks = [];

      for (let i = 1; i < 10; i++) {
        blocks[i] = jQuery("#block-" + i);
        blocks[i] = jQuery(jQuery(blocks[i])).offset().top;
      }


      /* Fixing features menu left & add margin-left: 250px; by content block */
      if (jQuery(this).scrollTop() + 96 > blocks[1] && jQuery(this).scrollTop() < blocks[9] - 100) {
        featuresmenuleft.addClass("fixed");
        featurescontentpointofsale.addClass("fixed");
        featuresmenuleft.removeClass("bottom");
      }
      else if (jQuery(this).scrollTop() + 114 > blocks[9]) {
        featuresmenuleft.removeClass("fixed");
        featuresmenuleft.addClass("bottom");
      }
      /* When u back scroll in top position */
      else {
        featuresmenuleft.removeClass("fixed");
        featurescontentpointofsale.removeClass("fixed");
      }



      if (jQuery(this).scrollTop() + 148 > blocks[2]) {
        jQuery(".block-1").removeClass("active");
      }
      else {
        jQuery(".block-1").addClass("active");
      }

      for (let i = 2; i < 9; i++) {
        if (jQuery(this).scrollTop() + 148 > blocks[i] && jQuery(this).scrollTop() + 148 < blocks[i + 1]) {
          jQuery(".block-" + [i]).addClass("active");
        }
        else {
          jQuery(".block-" + [i]).removeClass("active");
        }
      }

      if (jQuery(this).scrollTop() + 148 > blocks[9]) {
        jQuery(".block-9").addClass("active");
      }
      else {
        jQuery(".block-9").removeClass("active");
      }
    }
  }

  /* LINKS BY CLICK Features SumUp description */
  const sumup = document.querySelectorAll(".link-without-a")

  function moveOnLink(e) {
    e.preventDefault();
    window.open(this.dataset.href,"_self")
  }

  sumup.forEach(div => div.addEventListener('click', moveOnLink))
  /* END LINKS BY CLICK Features SumUp description */

});
;
/*
 Create Ioan Sche by 28.04.2020
*/


jQuery(".pay-annually").click(function () {
  jQuery(".price-monthly").addClass("disableform");
  jQuery(".appExplanation.month").addClass("disableform");
  jQuery(".price-annually").removeClass("disableform");
  jQuery(".appExplanation.year").removeClass("disableform");
  jQuery(".pay-annually").addClass("add-ons-active");
  jQuery(".pay-monthly").removeClass("add-ons-active");
});
jQuery(".pay-monthly").click(function () {
  jQuery(".price-annually").addClass("disableform");
  jQuery(".appExplanation.year").addClass("disableform");
  jQuery(".price-monthly").removeClass("disableform");
  jQuery(".appExplanation.month").removeClass("disableform");
  jQuery(".pay-monthly").addClass("add-ons-active");
  jQuery(".pay-annually").removeClass("add-ons-active");
});

jQuery(".inventory-annually, .employee-annually").click(function (event) {
  event.preventDefault();
  jQuery(".title.month").addClass("disableform");
  jQuery(".title.year").removeClass("disableform");
  jQuery(".save-2-months").removeClass("disableform");
  jQuery(".months_free_mob").removeClass("disableform");
  /* remove active classes from the adjacent button */
  jQuery(".employee-monthly").removeClass("active");
  jQuery(".inventory-monthly").removeClass("active");
  jQuery(".employee-annually").addClass("active");
  jQuery(".inventory-annually").addClass("active");
});
jQuery(".inventory-monthly, .employee-monthly").click(function (event) {
  event.preventDefault();
  jQuery(".title.month").removeClass("disableform");
  jQuery(".title.year").addClass("disableform");
  jQuery(".save-2-months").addClass("disableform");
  jQuery(".months_free_mob").addClass("disableform");
  /* remove active classes from the adjacent button */
  jQuery(".employee-annually").removeClass("active");
  jQuery(".inventory-annually").removeClass("active");
  jQuery(".employee-monthly").addClass("active");
  jQuery(".inventory-monthly").addClass("active");
});



window.addEventListener('load', function () {
  let siteurl = window.location.href;
  if (siteurl === "http://drupal8.local/pricing") {
    let article = document.getElementsByTagName("article");
    let dataHistoryNodeId = article[0].getAttribute("data-history-node-id");
    if (dataHistoryNodeId === "8") {
      let contentTextpad = document.getElementsByClassName("content-textpad")[0];
      contentTextpad.classList.add("pricing");
    }
  }


  /*  LINKS BY CLICK Pricing products  */
  const products = document.querySelectorAll(".block-pricing")

  function moveOnLink(e) {
    e.preventDefault();
    window.open(this.dataset.href,"_self")
  }

  products.forEach(div => div.addEventListener('click', moveOnLink))
  /*  END LINKS BY CLICK Pricing products  */
});

;

jQuery(".parent .clickquestion").click(function () {
  if (jQuery(this).parent(".parent").hasClass("selected")) {
    jQuery(this).parent(".parent").removeClass("selected");
  }
  else {
    jQuery(this).parent(".parent").addClass("selected");
  }
});
;
//
// Create Ioan Sche by 11.04.2020
//


/* Menu Open */
jQuery('.mobile-button').click(function (event) {
  event.preventDefault();
  jQuery('#overlay').fadeIn(400, function () {
    jQuery("body").css('overflow', 'hidden');
    if (jQuery("html").attr('dir') === "rtl") {
    // if (jQuery("body").hasClass("lang-ur") || jQuery("body").hasClass("lang-ar")) {
      jQuery('.topmenu.mobile').css('display', 'block').animate({
        right: '0'
      }, 300);
    }
    else {
      jQuery('.topmenu.mobile').css('display', 'block').animate({
        left: '0'
      }, 300);
    }
  });
});

/* close the menu by clicking on the cross icon or by clicking on overlay */
jQuery('#block-topmenumobilearrow, #overlay').click(function () {
// jQuery('.mobile-arrow, #overlay').click(function () {
  jQuery("body").css('overflow', 'unset');
  /* if the Arabic version, then the block moves out from right to left */
  if (jQuery("html").attr('dir') === "rtl") {
  // if (jQuery("body").hasClass("lang-ur") || jQuery("body").hasClass("lang-ar")) {
    jQuery('.topmenu.mobile').animate({
      right: '-225px'
    }, 300, function () {
      jQuery(this).css('display', 'none');
      jQuery('#overlay').fadeOut(400);
    });
  }
  else {
    jQuery('.topmenu.mobile').animate({
      left: '-225px'
    }, 300, function () {
      jQuery(this).css('display', 'none');
      jQuery('#overlay').fadeOut(400);
    });
  }
});



/* Add attributes for the logo */
let logo = document.getElementById("block-sitebranding-2");
if (logo) {
  logo.querySelector("a img").setAttribute("width", "97");
  logo.querySelector("a img").setAttribute("height", "26");
  logo.querySelector("a img").setAttribute("alt", "WamsCo Cloud");
}
/*  END Add attributes for the logo */



/* listening to full loading of the content page */
window.addEventListener('load', function () {
  // console.log(jQuery("#block-views-block-products-block-1-4 h3"))
  jQuery("#block-views-block-products-block-1-4 > :first-child").on('click touch', function () {
    if (jQuery(this).parent("#block-views-block-products-block-1-4").hasClass("selected")) {
      jQuery(this).parent("#block-views-block-products-block-1-4").removeClass("selected");
    }
    else {
      jQuery(this).parent("#block-views-block-products-block-1-4").addClass("selected");
      /*hide other open menu*/
      jQuery("#block-views-block-products-block-3-3").removeClass("selected");
      jQuery("#block-views-block-products-block-6").removeClass("selected");
      jQuery("#block-languageswitcher-3").removeClass("selected");
      jQuery("#block-product-top").removeClass("selected");
    }
  });

  // console.log(jQuery("#block-views-block-products-block-3-3 h3"))
  jQuery("#block-views-block-products-block-3-3 > :first-child").on('click touch', function () {
  // jQuery("#block-views-block-products-block-3-3 h3").click(function () {
    if (jQuery(this).parent("#block-views-block-products-block-3-3").hasClass("selected")) {
      jQuery(this).parent("#block-views-block-products-block-3-3").removeClass("selected");
    }
    else {
      jQuery(this).parent("#block-views-block-products-block-3-3").addClass("selected");
      /*hide other open menu*/
      jQuery("#block-views-block-products-block-1-4").removeClass("selected");
      jQuery("#block-views-block-products-block-6").removeClass("selected");
      jQuery("#block-languageswitcher-3").removeClass("selected");
      jQuery("#block-product-top").removeClass("selected");
    }
  });

  jQuery("#block-views-block-products-block-6 > :first-child").on('click touch', function () {
  // jQuery("#block-views-block-products-block-6 h3").click(function () {
    if (jQuery(this).parent("#block-views-block-products-block-6").hasClass("selected")) {
      jQuery(this).parent("#block-views-block-products-block-6").removeClass("selected");
    }
    else {
      jQuery(this).parent("#block-views-block-products-block-6").addClass("selected");
      /*hide other open menu*/
      jQuery("#block-views-block-products-block-1-4").removeClass("selected");
      jQuery("#block-views-block-products-block-3-3").removeClass("selected");
      jQuery("#block-languageswitcher-3").removeClass("selected");
      jQuery("#block-product-top").removeClass("selected");
    }
  });

  jQuery("#block-languageswitcher-3 > :nth-child(2)").on('click touch', function () {
    if (jQuery(this).parent("#block-languageswitcher-3").hasClass("selected")) {
      jQuery(this).parent("#block-languageswitcher-3").removeClass("selected");
    }
    else {
      jQuery(this).parent("#block-languageswitcher-3").addClass("selected");
      /*hide other open menu*/
      jQuery("#block-views-block-products-block-1-4").removeClass("selected");
      jQuery("#block-views-block-products-block-3-3").removeClass("selected");
      jQuery("#block-views-block-products-block-6").removeClass("selected");
      jQuery("#block-product-top").removeClass("selected");
    }
  });

  jQuery("#block-product-top-menu").on('click touch', function () {
    if (jQuery(this).parent("#block-product-top").hasClass("selected")) {
      jQuery(this).parent("#block-product-top").removeClass("selected");
    }
    else {
      jQuery(this).parent("#block-product-top").addClass("selected");
      jQuery("#block-languageswitcher-3").removeClass("selected");
      jQuery("#block-views-block-products-block-1-4").removeClass("selected");
      jQuery("#block-views-block-products-block-3-3").removeClass("selected");
      jQuery("#block-views-block-products-block-6").removeClass("selected");
    }
  });



  let menuPOSSystem = document.getElementById("block-views-block-products-block-3-3")
  let POSSystemMenu = menuPOSSystem.getElementsByClassName("menu")[0]
  let POSSystemmenuAllItems = POSSystemMenu.querySelectorAll("a")

  let menuIndustries = document.getElementById("block-views-block-products-block-6")
  let industriesMenu = menuIndustries.getElementsByClassName("menu")[0]
  let IndustriesmenuAllItems = industriesMenu.querySelectorAll("a")

  let contentTextpad = document.getElementsByClassName("content-textpad")[0]
  let AttributeId = contentTextpad.getElementsByTagName("article")[0]
  AttributeId = AttributeId.getAttribute("data-history-node-id")


  /* OPEN MOBILE MENU   Business types */
  if (AttributeId === "155") {/*  Grocery store  */
    IndustriesmenuAllItems[0].style.color = "#0092d9"
  }
  if (AttributeId === "22") {/*  Café  */
    IndustriesmenuAllItems[1].style.color = "#0092d9"
  }
  if (AttributeId === "24") {/*  Restaurant  */
    IndustriesmenuAllItems[2].style.color = "#0092d9"
  }
  if (AttributeId === "20") {/*  Bar  */
    IndustriesmenuAllItems[4].style.color = "#0092d9"
  }
  if (AttributeId === "25") {/*  Retail POS System  */
    IndustriesmenuAllItems[3].style.color = "#0092d9"
  }
  if (AttributeId === "21") {/*   Butick Fashion Retail Software  */
    IndustriesmenuAllItems[5].style.color = "#0092d9"
  }
  if (AttributeId === "23") {/*  Point of Sale System for Small Business   */
    let sb = document.getElementById("block-smallbusiness-2")
    sb.querySelector('.lmenu a').style.color = "#0092d9"
  }



  /* OPEN MOBILE MENU   POS System  */
  //<a href="/loyalty-program" hreflang="en">Loyalty Program</a>
  if (AttributeId === "19") {
    POSSystemmenuAllItems[0].style.color = "#0092d9"
  }
  //<a href="/payment-systems" hreflang="en">Accept Credit Cards</a>
  if (AttributeId === "33") {
    POSSystemmenuAllItems[1].style.color = "#0092d9"
  }
  //<a href="/sumup" hreflang="en">SumUp</a>
  if (AttributeId === "37") {
    POSSystemmenuAllItems[2].style.color = "#0092d9"
  }
 
  if (AttributeId === "58") {
    POSSystemmenuAllItems[3].style.color = "#0092d9"
  }
});

;



window.addEventListener('load', function () {
  let siteurl = window.location.href;
  if (siteurl === "http://wamsco-cloud.net") {
    let article = document.getElementsByTagName("article");
    let dataHistoryNodeId = article[0].getAttribute("data-history-node-id");

    if (dataHistoryNodeId === "46") {
      let contentTextpad = document.getElementsByClassName("content-textpad")[0];
      contentTextpad.classList.add("download");
    }
  }


});
;
/*
 *  Create Ioan Sche by 11.04.2020
*/

window.addEventListener('load', function () {

  let idTab = document.getElementById("tab");

  if (idTab) {
    window.addEventListener('scroll', function (el) {
      let block = jQuery("#tab8")
      let topLast = block.offset().top
      let heightLast = block.height()
      let heightMenu = jQuery('#tab .tabs_wrapper').height()
      let scrollTop = document.documentElement.scrollTop
      if (document.documentElement.clientWidth > 960) {
        if (scrollTop > 1184
          && scrollTop < topLast + heightLast) {
          idTab.classList.add("tab-fix");
          /*64 padding bloc tab1 + 58px heitgh block tab */
          document.getElementById("tab1").style.paddingTop = "156px";
        }
        else {
          idTab.classList.remove("tab-fix");
          jQuery('.tab_link.active').removeClass('active');
          document.getElementById("tab1").style.paddingTop = "64px";
        }
      }
      else {
        if ( scrollTop > 1140
          && scrollTop + heightMenu <= topLast + heightLast ) {
          idTab.classList.add("tab-fix");
        }
        else {
          idTab.classList.remove("tab-fix");
          document.getElementById("tab1").style.paddingTop = "16px";
        }
      }
    });
  }


  jQuery(".tabs_wrapper").on("click","a", function (event) {
    /* we exclude the standard browser reaction */
    event.preventDefault();
    /* get block id from href attribute */
    let id = jQuery(this).attr('href');
    /* find the height at which the block is located */
    let top = jQuery(id).offset().top - 100;
    if (document.documentElement.clientWidth < 961) {
      top = jQuery(id).offset().top - 40;
    }
    /* animate the transition to the block, time: 800 ms */
    jQuery('body,html').animate({
      scrollTop: top
    }, 500);
  });

  jQuery(window).scroll(function () {
    /* select each .tabcontent block and render it */
    jQuery('.hp_tools_content').each(function (i,el) {
      /* the top of the block is -54 height per header and 46 so that the block does not scroll right up to the top */
      let top = jQuery(el).offset().top - 140;
      if (document.documentElement.clientWidth < 961) {
        top = jQuery(el).offset().top - 100;
      }
      /* calculate the bottom point of the block */
      let bottom = top + jQuery(el).height();
      /* track the scroll */
      let scroll = jQuery(window).scrollTop();
      /* calculate block ID by attribute */
      let id = jQuery(el).attr('id');

      if (scroll > top && scroll < bottom) {
        jQuery('.tab_link.active').removeClass('active');
        jQuery('a[href="#' + id + '"]').addClass('active');
      }
    })
  });


  /*  VIDEO FROM YOUTUBE */
  let video = document.getElementById("vidos");
  let pop_up = document.getElementById("video_block");
  let popup_closer = pop_up.querySelector(".popup_wrapper .mobile-arrow");
  let popup_cont = pop_up.getElementsByTagName("iframe")[0];
  let overlay = document.getElementById("overlay");

  if (video) {
    video.addEventListener('click', function (e) {
      /* cancel the launch of the video by clicking on the link */
      e.preventDefault();
      /* remove the scrolling for the body so that the content does not scroll */
      jQuery("body").css('overflow', 'hidden');
      /* showing the video block */
      pop_up.classList.add("active");
      /* darken the background */
      overlay.classList.add("popup_active");
      /* prescribe which link to take */
      popup_cont.setAttribute("src", video.getAttribute("href"));
      /* calculate the dimensions of the screen and minus the indents around the perimeter 25% */
      let w = (document.documentElement.clientWidth * 0.75);
      let h = (document.documentElement.clientHeight * 0.75);
      let style = "width: " + w + "px; height: " + h + "px";
      popup_cont.setAttribute("style", style);
    }, false);
  }

  /* in the window that opens, listen to clicking on the close icon */
  popup_closer.addEventListener('click', function () {
    jQuery("body").css('overflow', 'unset');
    overlay.classList.remove("popup_active");
    if (pop_up.classList.contains('active')) {
      pop_up.classList.remove("active");
      popup_cont.setAttribute("src", "");
    }
    else {
      pop_up.classList.add("active");
    }
  }, false);
  /*  END VIDEO FROM YOUTUBE */


  /* Add attributes for the logo */
  let logo = document.getElementById("block-sitebranding");

  if (logo) {
    logo.setAttribute("itemscope", "");
    logo.setAttribute("itemtype", "http://www.schema.org/Organization");
    logo.querySelector("a img").setAttribute("alt", "WamsCo Cloud");
    logo.querySelector("a img").setAttribute("width", "142");
    logo.querySelector("a img").setAttribute("height", "38");
  }
  /* END Add attributes for the logo */


  /* LINKS BY CLICK Our products */
  const products = document.querySelectorAll(".hp_products_wrapper .product")

  function moveOnLink(e) {
    e.preventDefault();
    window.open(this.dataset.href,"_self")
  }

  products.forEach(div => div.addEventListener('click', moveOnLink))
  /* END LINKS BY CLICK Our products */


});
;
/*
     _ _      _       _
 ___| (_) ___| | __  (_)___
/ __| | |/ __| |/ /  | / __|
\__ \ | | (__|   < _ | \__ \
|___/_|_|\___|_|\_(_)/ |___/
                   |__/

 Version: 1.9.0
  Author: Ken Wheeler
 Website: http://kenwheeler.github.io
    Docs: http://kenwheeler.github.io/slick
    Repo: http://github.com/kenwheeler/slick
  Issues: http://github.com/kenwheeler/slick/issues

 */
(function(i){"use strict";"function"==typeof define&&define.amd?define(["jquery"],i):"undefined"!=typeof exports?module.exports=i(require("jquery")):i(jQuery)})(function(i){"use strict";var e=window.Slick||{};e=function(){function e(e,o){var s,n=this;n.defaults={accessibility:!0,adaptiveHeight:!1,appendArrows:i(e),appendDots:i(e),arrows:!0,asNavFor:null,prevArrow:'<button class="slick-prev" aria-label="Previous" type="button">Previous</button>',nextArrow:'<button class="slick-next" aria-label="Next" type="button">Next</button>',autoplay:!1,autoplaySpeed:3e3,centerMode:!1,centerPadding:"50px",cssEase:"ease",customPaging:function(e,t){return i('<button type="button" />').text(t+1)},dots:!1,dotsClass:"slick-dots",draggable:!0,easing:"linear",edgeFriction:.35,fade:!1,focusOnSelect:!1,focusOnChange:!1,infinite:!0,initialSlide:0,lazyLoad:"ondemand",mobileFirst:!1,pauseOnHover:!0,pauseOnFocus:!0,pauseOnDotsHover:!1,respondTo:"window",responsive:null,rows:1,rtl:!1,slide:"",slidesPerRow:1,slidesToShow:1,slidesToScroll:1,speed:500,swipe:!0,swipeToSlide:!1,touchMove:!0,touchThreshold:5,useCSS:!0,useTransform:!0,variableWidth:!1,vertical:!1,verticalSwiping:!1,waitForAnimate:!0,zIndex:1e3},n.initials={animating:!1,dragging:!1,autoPlayTimer:null,currentDirection:0,currentLeft:null,currentSlide:0,direction:1,$dots:null,listWidth:null,listHeight:null,loadIndex:0,$nextArrow:null,$prevArrow:null,scrolling:!1,slideCount:null,slideWidth:null,$slideTrack:null,$slides:null,sliding:!1,slideOffset:0,swipeLeft:null,swiping:!1,$list:null,touchObject:{},transformsEnabled:!1,unslicked:!1},i.extend(n,n.initials),n.activeBreakpoint=null,n.animType=null,n.animProp=null,n.breakpoints=[],n.breakpointSettings=[],n.cssTransitions=!1,n.focussed=!1,n.interrupted=!1,n.hidden="hidden",n.paused=!0,n.positionProp=null,n.respondTo=null,n.rowCount=1,n.shouldClick=!0,n.$slider=i(e),n.$slidesCache=null,n.transformType=null,n.transitionType=null,n.visibilityChange="visibilitychange",n.windowWidth=0,n.windowTimer=null,s=i(e).data("slick")||{},n.options=i.extend({},n.defaults,o,s),n.currentSlide=n.options.initialSlide,n.originalSettings=n.options,"undefined"!=typeof document.mozHidden?(n.hidden="mozHidden",n.visibilityChange="mozvisibilitychange"):"undefined"!=typeof document.webkitHidden&&(n.hidden="webkitHidden",n.visibilityChange="webkitvisibilitychange"),n.autoPlay=i.proxy(n.autoPlay,n),n.autoPlayClear=i.proxy(n.autoPlayClear,n),n.autoPlayIterator=i.proxy(n.autoPlayIterator,n),n.changeSlide=i.proxy(n.changeSlide,n),n.clickHandler=i.proxy(n.clickHandler,n),n.selectHandler=i.proxy(n.selectHandler,n),n.setPosition=i.proxy(n.setPosition,n),n.swipeHandler=i.proxy(n.swipeHandler,n),n.dragHandler=i.proxy(n.dragHandler,n),n.keyHandler=i.proxy(n.keyHandler,n),n.instanceUid=t++,n.htmlExpr=/^(?:\s*(<[\w\W]+>)[^>]*)$/,n.registerBreakpoints(),n.init(!0)}var t=0;return e}(),e.prototype.activateADA=function(){var i=this;i.$slideTrack.find(".slick-active").attr({"aria-hidden":"false"}).find("a, input, button, select").attr({tabindex:"0"})},e.prototype.addSlide=e.prototype.slickAdd=function(e,t,o){var s=this;if("boolean"==typeof t)o=t,t=null;else if(t<0||t>=s.slideCount)return!1;s.unload(),"number"==typeof t?0===t&&0===s.$slides.length?i(e).appendTo(s.$slideTrack):o?i(e).insertBefore(s.$slides.eq(t)):i(e).insertAfter(s.$slides.eq(t)):o===!0?i(e).prependTo(s.$slideTrack):i(e).appendTo(s.$slideTrack),s.$slides=s.$slideTrack.children(this.options.slide),s.$slideTrack.children(this.options.slide).detach(),s.$slideTrack.append(s.$slides),s.$slides.each(function(e,t){i(t).attr("data-slick-index",e)}),s.$slidesCache=s.$slides,s.reinit()},e.prototype.animateHeight=function(){var i=this;if(1===i.options.slidesToShow&&i.options.adaptiveHeight===!0&&i.options.vertical===!1){var e=i.$slides.eq(i.currentSlide).outerHeight(!0);i.$list.animate({height:e},i.options.speed)}},e.prototype.animateSlide=function(e,t){var o={},s=this;s.animateHeight(),s.options.rtl===!0&&s.options.vertical===!1&&(e=-e),s.transformsEnabled===!1?s.options.vertical===!1?s.$slideTrack.animate({left:e},s.options.speed,s.options.easing,t):s.$slideTrack.animate({top:e},s.options.speed,s.options.easing,t):s.cssTransitions===!1?(s.options.rtl===!0&&(s.currentLeft=-s.currentLeft),i({animStart:s.currentLeft}).animate({animStart:e},{duration:s.options.speed,easing:s.options.easing,step:function(i){i=Math.ceil(i),s.options.vertical===!1?(o[s.animType]="translate("+i+"px, 0px)",s.$slideTrack.css(o)):(o[s.animType]="translate(0px,"+i+"px)",s.$slideTrack.css(o))},complete:function(){t&&t.call()}})):(s.applyTransition(),e=Math.ceil(e),s.options.vertical===!1?o[s.animType]="translate3d("+e+"px, 0px, 0px)":o[s.animType]="translate3d(0px,"+e+"px, 0px)",s.$slideTrack.css(o),t&&setTimeout(function(){s.disableTransition(),t.call()},s.options.speed))},e.prototype.getNavTarget=function(){var e=this,t=e.options.asNavFor;return t&&null!==t&&(t=i(t).not(e.$slider)),t},e.prototype.asNavFor=function(e){var t=this,o=t.getNavTarget();null!==o&&"object"==typeof o&&o.each(function(){var t=i(this).slick("getSlick");t.unslicked||t.slideHandler(e,!0)})},e.prototype.applyTransition=function(i){var e=this,t={};e.options.fade===!1?t[e.transitionType]=e.transformType+" "+e.options.speed+"ms "+e.options.cssEase:t[e.transitionType]="opacity "+e.options.speed+"ms "+e.options.cssEase,e.options.fade===!1?e.$slideTrack.css(t):e.$slides.eq(i).css(t)},e.prototype.autoPlay=function(){var i=this;i.autoPlayClear(),i.slideCount>i.options.slidesToShow&&(i.autoPlayTimer=setInterval(i.autoPlayIterator,i.options.autoplaySpeed))},e.prototype.autoPlayClear=function(){var i=this;i.autoPlayTimer&&clearInterval(i.autoPlayTimer)},e.prototype.autoPlayIterator=function(){var i=this,e=i.currentSlide+i.options.slidesToScroll;i.paused||i.interrupted||i.focussed||(i.options.infinite===!1&&(1===i.direction&&i.currentSlide+1===i.slideCount-1?i.direction=0:0===i.direction&&(e=i.currentSlide-i.options.slidesToScroll,i.currentSlide-1===0&&(i.direction=1))),i.slideHandler(e))},e.prototype.buildArrows=function(){var e=this;e.options.arrows===!0&&(e.$prevArrow=i(e.options.prevArrow).addClass("slick-arrow"),e.$nextArrow=i(e.options.nextArrow).addClass("slick-arrow"),e.slideCount>e.options.slidesToShow?(e.$prevArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),e.$nextArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),e.htmlExpr.test(e.options.prevArrow)&&e.$prevArrow.prependTo(e.options.appendArrows),e.htmlExpr.test(e.options.nextArrow)&&e.$nextArrow.appendTo(e.options.appendArrows),e.options.infinite!==!0&&e.$prevArrow.addClass("slick-disabled").attr("aria-disabled","true")):e.$prevArrow.add(e.$nextArrow).addClass("slick-hidden").attr({"aria-disabled":"true",tabindex:"-1"}))},e.prototype.buildDots=function(){var e,t,o=this;if(o.options.dots===!0&&o.slideCount>o.options.slidesToShow){for(o.$slider.addClass("slick-dotted"),t=i("<ul />").addClass(o.options.dotsClass),e=0;e<=o.getDotCount();e+=1)t.append(i("<li />").append(o.options.customPaging.call(this,o,e)));o.$dots=t.appendTo(o.options.appendDots),o.$dots.find("li").first().addClass("slick-active")}},e.prototype.buildOut=function(){var e=this;e.$slides=e.$slider.children(e.options.slide+":not(.slick-cloned)").addClass("slick-slide"),e.slideCount=e.$slides.length,e.$slides.each(function(e,t){i(t).attr("data-slick-index",e).data("originalStyling",i(t).attr("style")||"")}),e.$slider.addClass("slick-slider"),e.$slideTrack=0===e.slideCount?i('<div class="slick-track"/>').appendTo(e.$slider):e.$slides.wrapAll('<div class="slick-track"/>').parent(),e.$list=e.$slideTrack.wrap('<div class="slick-list"/>').parent(),e.$slideTrack.css("opacity",0),e.options.centerMode!==!0&&e.options.swipeToSlide!==!0||(e.options.slidesToScroll=1),i("img[data-lazy]",e.$slider).not("[src]").addClass("slick-loading"),e.setupInfinite(),e.buildArrows(),e.buildDots(),e.updateDots(),e.setSlideClasses("number"==typeof e.currentSlide?e.currentSlide:0),e.options.draggable===!0&&e.$list.addClass("draggable")},e.prototype.buildRows=function(){var i,e,t,o,s,n,r,l=this;if(o=document.createDocumentFragment(),n=l.$slider.children(),l.options.rows>0){for(r=l.options.slidesPerRow*l.options.rows,s=Math.ceil(n.length/r),i=0;i<s;i++){var d=document.createElement("div");for(e=0;e<l.options.rows;e++){var a=document.createElement("div");for(t=0;t<l.options.slidesPerRow;t++){var c=i*r+(e*l.options.slidesPerRow+t);n.get(c)&&a.appendChild(n.get(c))}d.appendChild(a)}o.appendChild(d)}l.$slider.empty().append(o),l.$slider.children().children().children().css({width:100/l.options.slidesPerRow+"%",display:"inline-block"})}},e.prototype.checkResponsive=function(e,t){var o,s,n,r=this,l=!1,d=r.$slider.width(),a=window.innerWidth||i(window).width();if("window"===r.respondTo?n=a:"slider"===r.respondTo?n=d:"min"===r.respondTo&&(n=Math.min(a,d)),r.options.responsive&&r.options.responsive.length&&null!==r.options.responsive){s=null;for(o in r.breakpoints)r.breakpoints.hasOwnProperty(o)&&(r.originalSettings.mobileFirst===!1?n<r.breakpoints[o]&&(s=r.breakpoints[o]):n>r.breakpoints[o]&&(s=r.breakpoints[o]));null!==s?null!==r.activeBreakpoint?(s!==r.activeBreakpoint||t)&&(r.activeBreakpoint=s,"unslick"===r.breakpointSettings[s]?r.unslick(s):(r.options=i.extend({},r.originalSettings,r.breakpointSettings[s]),e===!0&&(r.currentSlide=r.options.initialSlide),r.refresh(e)),l=s):(r.activeBreakpoint=s,"unslick"===r.breakpointSettings[s]?r.unslick(s):(r.options=i.extend({},r.originalSettings,r.breakpointSettings[s]),e===!0&&(r.currentSlide=r.options.initialSlide),r.refresh(e)),l=s):null!==r.activeBreakpoint&&(r.activeBreakpoint=null,r.options=r.originalSettings,e===!0&&(r.currentSlide=r.options.initialSlide),r.refresh(e),l=s),e||l===!1||r.$slider.trigger("breakpoint",[r,l])}},e.prototype.changeSlide=function(e,t){var o,s,n,r=this,l=i(e.currentTarget);switch(l.is("a")&&e.preventDefault(),l.is("li")||(l=l.closest("li")),n=r.slideCount%r.options.slidesToScroll!==0,o=n?0:(r.slideCount-r.currentSlide)%r.options.slidesToScroll,e.data.message){case"previous":s=0===o?r.options.slidesToScroll:r.options.slidesToShow-o,r.slideCount>r.options.slidesToShow&&r.slideHandler(r.currentSlide-s,!1,t);break;case"next":s=0===o?r.options.slidesToScroll:o,r.slideCount>r.options.slidesToShow&&r.slideHandler(r.currentSlide+s,!1,t);break;case"index":var d=0===e.data.index?0:e.data.index||l.index()*r.options.slidesToScroll;r.slideHandler(r.checkNavigable(d),!1,t),l.children().trigger("focus");break;default:return}},e.prototype.checkNavigable=function(i){var e,t,o=this;if(e=o.getNavigableIndexes(),t=0,i>e[e.length-1])i=e[e.length-1];else for(var s in e){if(i<e[s]){i=t;break}t=e[s]}return i},e.prototype.cleanUpEvents=function(){var e=this;e.options.dots&&null!==e.$dots&&(i("li",e.$dots).off("click.slick",e.changeSlide).off("mouseenter.slick",i.proxy(e.interrupt,e,!0)).off("mouseleave.slick",i.proxy(e.interrupt,e,!1)),e.options.accessibility===!0&&e.$dots.off("keydown.slick",e.keyHandler)),e.$slider.off("focus.slick blur.slick"),e.options.arrows===!0&&e.slideCount>e.options.slidesToShow&&(e.$prevArrow&&e.$prevArrow.off("click.slick",e.changeSlide),e.$nextArrow&&e.$nextArrow.off("click.slick",e.changeSlide),e.options.accessibility===!0&&(e.$prevArrow&&e.$prevArrow.off("keydown.slick",e.keyHandler),e.$nextArrow&&e.$nextArrow.off("keydown.slick",e.keyHandler))),e.$list.off("touchstart.slick mousedown.slick",e.swipeHandler),e.$list.off("touchmove.slick mousemove.slick",e.swipeHandler),e.$list.off("touchend.slick mouseup.slick",e.swipeHandler),e.$list.off("touchcancel.slick mouseleave.slick",e.swipeHandler),e.$list.off("click.slick",e.clickHandler),i(document).off(e.visibilityChange,e.visibility),e.cleanUpSlideEvents(),e.options.accessibility===!0&&e.$list.off("keydown.slick",e.keyHandler),e.options.focusOnSelect===!0&&i(e.$slideTrack).children().off("click.slick",e.selectHandler),i(window).off("orientationchange.slick.slick-"+e.instanceUid,e.orientationChange),i(window).off("resize.slick.slick-"+e.instanceUid,e.resize),i("[draggable!=true]",e.$slideTrack).off("dragstart",e.preventDefault),i(window).off("load.slick.slick-"+e.instanceUid,e.setPosition)},e.prototype.cleanUpSlideEvents=function(){var e=this;e.$list.off("mouseenter.slick",i.proxy(e.interrupt,e,!0)),e.$list.off("mouseleave.slick",i.proxy(e.interrupt,e,!1))},e.prototype.cleanUpRows=function(){var i,e=this;e.options.rows>0&&(i=e.$slides.children().children(),i.removeAttr("style"),e.$slider.empty().append(i))},e.prototype.clickHandler=function(i){var e=this;e.shouldClick===!1&&(i.stopImmediatePropagation(),i.stopPropagation(),i.preventDefault())},e.prototype.destroy=function(e){var t=this;t.autoPlayClear(),t.touchObject={},t.cleanUpEvents(),i(".slick-cloned",t.$slider).detach(),t.$dots&&t.$dots.remove(),t.$prevArrow&&t.$prevArrow.length&&(t.$prevArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display",""),t.htmlExpr.test(t.options.prevArrow)&&t.$prevArrow.remove()),t.$nextArrow&&t.$nextArrow.length&&(t.$nextArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display",""),t.htmlExpr.test(t.options.nextArrow)&&t.$nextArrow.remove()),t.$slides&&(t.$slides.removeClass("slick-slide slick-active slick-center slick-visible slick-current").removeAttr("aria-hidden").removeAttr("data-slick-index").each(function(){i(this).attr("style",i(this).data("originalStyling"))}),t.$slideTrack.children(this.options.slide).detach(),t.$slideTrack.detach(),t.$list.detach(),t.$slider.append(t.$slides)),t.cleanUpRows(),t.$slider.removeClass("slick-slider"),t.$slider.removeClass("slick-initialized"),t.$slider.removeClass("slick-dotted"),t.unslicked=!0,e||t.$slider.trigger("destroy",[t])},e.prototype.disableTransition=function(i){var e=this,t={};t[e.transitionType]="",e.options.fade===!1?e.$slideTrack.css(t):e.$slides.eq(i).css(t)},e.prototype.fadeSlide=function(i,e){var t=this;t.cssTransitions===!1?(t.$slides.eq(i).css({zIndex:t.options.zIndex}),t.$slides.eq(i).animate({opacity:1},t.options.speed,t.options.easing,e)):(t.applyTransition(i),t.$slides.eq(i).css({opacity:1,zIndex:t.options.zIndex}),e&&setTimeout(function(){t.disableTransition(i),e.call()},t.options.speed))},e.prototype.fadeSlideOut=function(i){var e=this;e.cssTransitions===!1?e.$slides.eq(i).animate({opacity:0,zIndex:e.options.zIndex-2},e.options.speed,e.options.easing):(e.applyTransition(i),e.$slides.eq(i).css({opacity:0,zIndex:e.options.zIndex-2}))},e.prototype.filterSlides=e.prototype.slickFilter=function(i){var e=this;null!==i&&(e.$slidesCache=e.$slides,e.unload(),e.$slideTrack.children(this.options.slide).detach(),e.$slidesCache.filter(i).appendTo(e.$slideTrack),e.reinit())},e.prototype.focusHandler=function(){var e=this;e.$slider.off("focus.slick blur.slick").on("focus.slick","*",function(t){var o=i(this);setTimeout(function(){e.options.pauseOnFocus&&o.is(":focus")&&(e.focussed=!0,e.autoPlay())},0)}).on("blur.slick","*",function(t){i(this);e.options.pauseOnFocus&&(e.focussed=!1,e.autoPlay())})},e.prototype.getCurrent=e.prototype.slickCurrentSlide=function(){var i=this;return i.currentSlide},e.prototype.getDotCount=function(){var i=this,e=0,t=0,o=0;if(i.options.infinite===!0)if(i.slideCount<=i.options.slidesToShow)++o;else for(;e<i.slideCount;)++o,e=t+i.options.slidesToScroll,t+=i.options.slidesToScroll<=i.options.slidesToShow?i.options.slidesToScroll:i.options.slidesToShow;else if(i.options.centerMode===!0)o=i.slideCount;else if(i.options.asNavFor)for(;e<i.slideCount;)++o,e=t+i.options.slidesToScroll,t+=i.options.slidesToScroll<=i.options.slidesToShow?i.options.slidesToScroll:i.options.slidesToShow;else o=1+Math.ceil((i.slideCount-i.options.slidesToShow)/i.options.slidesToScroll);return o-1},e.prototype.getLeft=function(i){var e,t,o,s,n=this,r=0;return n.slideOffset=0,t=n.$slides.first().outerHeight(!0),n.options.infinite===!0?(n.slideCount>n.options.slidesToShow&&(n.slideOffset=n.slideWidth*n.options.slidesToShow*-1,s=-1,n.options.vertical===!0&&n.options.centerMode===!0&&(2===n.options.slidesToShow?s=-1.5:1===n.options.slidesToShow&&(s=-2)),r=t*n.options.slidesToShow*s),n.slideCount%n.options.slidesToScroll!==0&&i+n.options.slidesToScroll>n.slideCount&&n.slideCount>n.options.slidesToShow&&(i>n.slideCount?(n.slideOffset=(n.options.slidesToShow-(i-n.slideCount))*n.slideWidth*-1,r=(n.options.slidesToShow-(i-n.slideCount))*t*-1):(n.slideOffset=n.slideCount%n.options.slidesToScroll*n.slideWidth*-1,r=n.slideCount%n.options.slidesToScroll*t*-1))):i+n.options.slidesToShow>n.slideCount&&(n.slideOffset=(i+n.options.slidesToShow-n.slideCount)*n.slideWidth,r=(i+n.options.slidesToShow-n.slideCount)*t),n.slideCount<=n.options.slidesToShow&&(n.slideOffset=0,r=0),n.options.centerMode===!0&&n.slideCount<=n.options.slidesToShow?n.slideOffset=n.slideWidth*Math.floor(n.options.slidesToShow)/2-n.slideWidth*n.slideCount/2:n.options.centerMode===!0&&n.options.infinite===!0?n.slideOffset+=n.slideWidth*Math.floor(n.options.slidesToShow/2)-n.slideWidth:n.options.centerMode===!0&&(n.slideOffset=0,n.slideOffset+=n.slideWidth*Math.floor(n.options.slidesToShow/2)),e=n.options.vertical===!1?i*n.slideWidth*-1+n.slideOffset:i*t*-1+r,n.options.variableWidth===!0&&(o=n.slideCount<=n.options.slidesToShow||n.options.infinite===!1?n.$slideTrack.children(".slick-slide").eq(i):n.$slideTrack.children(".slick-slide").eq(i+n.options.slidesToShow),e=n.options.rtl===!0?o[0]?(n.$slideTrack.width()-o[0].offsetLeft-o.width())*-1:0:o[0]?o[0].offsetLeft*-1:0,n.options.centerMode===!0&&(o=n.slideCount<=n.options.slidesToShow||n.options.infinite===!1?n.$slideTrack.children(".slick-slide").eq(i):n.$slideTrack.children(".slick-slide").eq(i+n.options.slidesToShow+1),e=n.options.rtl===!0?o[0]?(n.$slideTrack.width()-o[0].offsetLeft-o.width())*-1:0:o[0]?o[0].offsetLeft*-1:0,e+=(n.$list.width()-o.outerWidth())/2)),e},e.prototype.getOption=e.prototype.slickGetOption=function(i){var e=this;return e.options[i]},e.prototype.getNavigableIndexes=function(){var i,e=this,t=0,o=0,s=[];for(e.options.infinite===!1?i=e.slideCount:(t=e.options.slidesToScroll*-1,o=e.options.slidesToScroll*-1,i=2*e.slideCount);t<i;)s.push(t),t=o+e.options.slidesToScroll,o+=e.options.slidesToScroll<=e.options.slidesToShow?e.options.slidesToScroll:e.options.slidesToShow;return s},e.prototype.getSlick=function(){return this},e.prototype.getSlideCount=function(){var e,t,o,s,n=this;return s=n.options.centerMode===!0?Math.floor(n.$list.width()/2):0,o=n.swipeLeft*-1+s,n.options.swipeToSlide===!0?(n.$slideTrack.find(".slick-slide").each(function(e,s){var r,l,d;if(r=i(s).outerWidth(),l=s.offsetLeft,n.options.centerMode!==!0&&(l+=r/2),d=l+r,o<d)return t=s,!1}),e=Math.abs(i(t).attr("data-slick-index")-n.currentSlide)||1):n.options.slidesToScroll},e.prototype.goTo=e.prototype.slickGoTo=function(i,e){var t=this;t.changeSlide({data:{message:"index",index:parseInt(i)}},e)},e.prototype.init=function(e){var t=this;i(t.$slider).hasClass("slick-initialized")||(i(t.$slider).addClass("slick-initialized"),t.buildRows(),t.buildOut(),t.setProps(),t.startLoad(),t.loadSlider(),t.initializeEvents(),t.updateArrows(),t.updateDots(),t.checkResponsive(!0),t.focusHandler()),e&&t.$slider.trigger("init",[t]),t.options.accessibility===!0&&t.initADA(),t.options.autoplay&&(t.paused=!1,t.autoPlay())},e.prototype.initADA=function(){var e=this,t=Math.ceil(e.slideCount/e.options.slidesToShow),o=e.getNavigableIndexes().filter(function(i){return i>=0&&i<e.slideCount});e.$slides.add(e.$slideTrack.find(".slick-cloned")).attr({"aria-hidden":"true",tabindex:"-1"}).find("a, input, button, select").attr({tabindex:"-1"}),null!==e.$dots&&(e.$slides.not(e.$slideTrack.find(".slick-cloned")).each(function(t){var s=o.indexOf(t);if(i(this).attr({role:"tabpanel",id:"slick-slide"+e.instanceUid+t,tabindex:-1}),s!==-1){var n="slick-slide-control"+e.instanceUid+s;i("#"+n).length&&i(this).attr({"aria-describedby":n})}}),e.$dots.attr("role","tablist").find("li").each(function(s){var n=o[s];i(this).attr({role:"presentation"}),i(this).find("button").first().attr({role:"tab",id:"slick-slide-control"+e.instanceUid+s,"aria-controls":"slick-slide"+e.instanceUid+n,"aria-label":s+1+" of "+t,"aria-selected":null,tabindex:"-1"})}).eq(e.currentSlide).find("button").attr({"aria-selected":"true",tabindex:"0"}).end());for(var s=e.currentSlide,n=s+e.options.slidesToShow;s<n;s++)e.options.focusOnChange?e.$slides.eq(s).attr({tabindex:"0"}):e.$slides.eq(s).removeAttr("tabindex");e.activateADA()},e.prototype.initArrowEvents=function(){var i=this;i.options.arrows===!0&&i.slideCount>i.options.slidesToShow&&(i.$prevArrow.off("click.slick").on("click.slick",{message:"previous"},i.changeSlide),i.$nextArrow.off("click.slick").on("click.slick",{message:"next"},i.changeSlide),i.options.accessibility===!0&&(i.$prevArrow.on("keydown.slick",i.keyHandler),i.$nextArrow.on("keydown.slick",i.keyHandler)))},e.prototype.initDotEvents=function(){var e=this;e.options.dots===!0&&e.slideCount>e.options.slidesToShow&&(i("li",e.$dots).on("click.slick",{message:"index"},e.changeSlide),e.options.accessibility===!0&&e.$dots.on("keydown.slick",e.keyHandler)),e.options.dots===!0&&e.options.pauseOnDotsHover===!0&&e.slideCount>e.options.slidesToShow&&i("li",e.$dots).on("mouseenter.slick",i.proxy(e.interrupt,e,!0)).on("mouseleave.slick",i.proxy(e.interrupt,e,!1))},e.prototype.initSlideEvents=function(){var e=this;e.options.pauseOnHover&&(e.$list.on("mouseenter.slick",i.proxy(e.interrupt,e,!0)),e.$list.on("mouseleave.slick",i.proxy(e.interrupt,e,!1)))},e.prototype.initializeEvents=function(){var e=this;e.initArrowEvents(),e.initDotEvents(),e.initSlideEvents(),e.$list.on("touchstart.slick mousedown.slick",{action:"start"},e.swipeHandler),e.$list.on("touchmove.slick mousemove.slick",{action:"move"},e.swipeHandler),e.$list.on("touchend.slick mouseup.slick",{action:"end"},e.swipeHandler),e.$list.on("touchcancel.slick mouseleave.slick",{action:"end"},e.swipeHandler),e.$list.on("click.slick",e.clickHandler),i(document).on(e.visibilityChange,i.proxy(e.visibility,e)),e.options.accessibility===!0&&e.$list.on("keydown.slick",e.keyHandler),e.options.focusOnSelect===!0&&i(e.$slideTrack).children().on("click.slick",e.selectHandler),i(window).on("orientationchange.slick.slick-"+e.instanceUid,i.proxy(e.orientationChange,e)),i(window).on("resize.slick.slick-"+e.instanceUid,i.proxy(e.resize,e)),i("[draggable!=true]",e.$slideTrack).on("dragstart",e.preventDefault),i(window).on("load.slick.slick-"+e.instanceUid,e.setPosition),i(e.setPosition)},e.prototype.initUI=function(){var i=this;i.options.arrows===!0&&i.slideCount>i.options.slidesToShow&&(i.$prevArrow.show(),i.$nextArrow.show()),i.options.dots===!0&&i.slideCount>i.options.slidesToShow&&i.$dots.show()},e.prototype.keyHandler=function(i){var e=this;i.target.tagName.match("TEXTAREA|INPUT|SELECT")||(37===i.keyCode&&e.options.accessibility===!0?e.changeSlide({data:{message:e.options.rtl===!0?"next":"previous"}}):39===i.keyCode&&e.options.accessibility===!0&&e.changeSlide({data:{message:e.options.rtl===!0?"previous":"next"}}))},e.prototype.lazyLoad=function(){function e(e){i("img[data-lazy]",e).each(function(){var e=i(this),t=i(this).attr("data-lazy"),o=i(this).attr("data-srcset"),s=i(this).attr("data-sizes")||r.$slider.attr("data-sizes"),n=document.createElement("img");n.onload=function(){e.animate({opacity:0},100,function(){o&&(e.attr("srcset",o),s&&e.attr("sizes",s)),e.attr("src",t).animate({opacity:1},200,function(){e.removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading")}),r.$slider.trigger("lazyLoaded",[r,e,t])})},n.onerror=function(){e.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"),r.$slider.trigger("lazyLoadError",[r,e,t])},n.src=t})}var t,o,s,n,r=this;if(r.options.centerMode===!0?r.options.infinite===!0?(s=r.currentSlide+(r.options.slidesToShow/2+1),n=s+r.options.slidesToShow+2):(s=Math.max(0,r.currentSlide-(r.options.slidesToShow/2+1)),n=2+(r.options.slidesToShow/2+1)+r.currentSlide):(s=r.options.infinite?r.options.slidesToShow+r.currentSlide:r.currentSlide,n=Math.ceil(s+r.options.slidesToShow),r.options.fade===!0&&(s>0&&s--,n<=r.slideCount&&n++)),t=r.$slider.find(".slick-slide").slice(s,n),"anticipated"===r.options.lazyLoad)for(var l=s-1,d=n,a=r.$slider.find(".slick-slide"),c=0;c<r.options.slidesToScroll;c++)l<0&&(l=r.slideCount-1),t=t.add(a.eq(l)),t=t.add(a.eq(d)),l--,d++;e(t),r.slideCount<=r.options.slidesToShow?(o=r.$slider.find(".slick-slide"),e(o)):r.currentSlide>=r.slideCount-r.options.slidesToShow?(o=r.$slider.find(".slick-cloned").slice(0,r.options.slidesToShow),e(o)):0===r.currentSlide&&(o=r.$slider.find(".slick-cloned").slice(r.options.slidesToShow*-1),e(o))},e.prototype.loadSlider=function(){var i=this;i.setPosition(),i.$slideTrack.css({opacity:1}),i.$slider.removeClass("slick-loading"),i.initUI(),"progressive"===i.options.lazyLoad&&i.progressiveLazyLoad()},e.prototype.next=e.prototype.slickNext=function(){var i=this;i.changeSlide({data:{message:"next"}})},e.prototype.orientationChange=function(){var i=this;i.checkResponsive(),i.setPosition()},e.prototype.pause=e.prototype.slickPause=function(){var i=this;i.autoPlayClear(),i.paused=!0},e.prototype.play=e.prototype.slickPlay=function(){var i=this;i.autoPlay(),i.options.autoplay=!0,i.paused=!1,i.focussed=!1,i.interrupted=!1},e.prototype.postSlide=function(e){var t=this;if(!t.unslicked&&(t.$slider.trigger("afterChange",[t,e]),t.animating=!1,t.slideCount>t.options.slidesToShow&&t.setPosition(),t.swipeLeft=null,t.options.autoplay&&t.autoPlay(),t.options.accessibility===!0&&(t.initADA(),t.options.focusOnChange))){var o=i(t.$slides.get(t.currentSlide));o.attr("tabindex",0).focus()}},e.prototype.prev=e.prototype.slickPrev=function(){var i=this;i.changeSlide({data:{message:"previous"}})},e.prototype.preventDefault=function(i){i.preventDefault()},e.prototype.progressiveLazyLoad=function(e){e=e||1;var t,o,s,n,r,l=this,d=i("img[data-lazy]",l.$slider);d.length?(t=d.first(),o=t.attr("data-lazy"),s=t.attr("data-srcset"),n=t.attr("data-sizes")||l.$slider.attr("data-sizes"),r=document.createElement("img"),r.onload=function(){s&&(t.attr("srcset",s),n&&t.attr("sizes",n)),t.attr("src",o).removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading"),l.options.adaptiveHeight===!0&&l.setPosition(),l.$slider.trigger("lazyLoaded",[l,t,o]),l.progressiveLazyLoad()},r.onerror=function(){e<3?setTimeout(function(){l.progressiveLazyLoad(e+1)},500):(t.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"),l.$slider.trigger("lazyLoadError",[l,t,o]),l.progressiveLazyLoad())},r.src=o):l.$slider.trigger("allImagesLoaded",[l])},e.prototype.refresh=function(e){var t,o,s=this;o=s.slideCount-s.options.slidesToShow,!s.options.infinite&&s.currentSlide>o&&(s.currentSlide=o),s.slideCount<=s.options.slidesToShow&&(s.currentSlide=0),t=s.currentSlide,s.destroy(!0),i.extend(s,s.initials,{currentSlide:t}),s.init(),e||s.changeSlide({data:{message:"index",index:t}},!1)},e.prototype.registerBreakpoints=function(){var e,t,o,s=this,n=s.options.responsive||null;if("array"===i.type(n)&&n.length){s.respondTo=s.options.respondTo||"window";for(e in n)if(o=s.breakpoints.length-1,n.hasOwnProperty(e)){for(t=n[e].breakpoint;o>=0;)s.breakpoints[o]&&s.breakpoints[o]===t&&s.breakpoints.splice(o,1),o--;s.breakpoints.push(t),s.breakpointSettings[t]=n[e].settings}s.breakpoints.sort(function(i,e){return s.options.mobileFirst?i-e:e-i})}},e.prototype.reinit=function(){var e=this;e.$slides=e.$slideTrack.children(e.options.slide).addClass("slick-slide"),e.slideCount=e.$slides.length,e.currentSlide>=e.slideCount&&0!==e.currentSlide&&(e.currentSlide=e.currentSlide-e.options.slidesToScroll),e.slideCount<=e.options.slidesToShow&&(e.currentSlide=0),e.registerBreakpoints(),e.setProps(),e.setupInfinite(),e.buildArrows(),e.updateArrows(),e.initArrowEvents(),e.buildDots(),e.updateDots(),e.initDotEvents(),e.cleanUpSlideEvents(),e.initSlideEvents(),e.checkResponsive(!1,!0),e.options.focusOnSelect===!0&&i(e.$slideTrack).children().on("click.slick",e.selectHandler),e.setSlideClasses("number"==typeof e.currentSlide?e.currentSlide:0),e.setPosition(),e.focusHandler(),e.paused=!e.options.autoplay,e.autoPlay(),e.$slider.trigger("reInit",[e])},e.prototype.resize=function(){var e=this;i(window).width()!==e.windowWidth&&(clearTimeout(e.windowDelay),e.windowDelay=window.setTimeout(function(){e.windowWidth=i(window).width(),e.checkResponsive(),e.unslicked||e.setPosition()},50))},e.prototype.removeSlide=e.prototype.slickRemove=function(i,e,t){var o=this;return"boolean"==typeof i?(e=i,i=e===!0?0:o.slideCount-1):i=e===!0?--i:i,!(o.slideCount<1||i<0||i>o.slideCount-1)&&(o.unload(),t===!0?o.$slideTrack.children().remove():o.$slideTrack.children(this.options.slide).eq(i).remove(),o.$slides=o.$slideTrack.children(this.options.slide),o.$slideTrack.children(this.options.slide).detach(),o.$slideTrack.append(o.$slides),o.$slidesCache=o.$slides,void o.reinit())},e.prototype.setCSS=function(i){var e,t,o=this,s={};o.options.rtl===!0&&(i=-i),e="left"==o.positionProp?Math.ceil(i)+"px":"0px",t="top"==o.positionProp?Math.ceil(i)+"px":"0px",s[o.positionProp]=i,o.transformsEnabled===!1?o.$slideTrack.css(s):(s={},o.cssTransitions===!1?(s[o.animType]="translate("+e+", "+t+")",o.$slideTrack.css(s)):(s[o.animType]="translate3d("+e+", "+t+", 0px)",o.$slideTrack.css(s)))},e.prototype.setDimensions=function(){var i=this;i.options.vertical===!1?i.options.centerMode===!0&&i.$list.css({padding:"0px "+i.options.centerPadding}):(i.$list.height(i.$slides.first().outerHeight(!0)*i.options.slidesToShow),i.options.centerMode===!0&&i.$list.css({padding:i.options.centerPadding+" 0px"})),i.listWidth=i.$list.width(),i.listHeight=i.$list.height(),i.options.vertical===!1&&i.options.variableWidth===!1?(i.slideWidth=Math.ceil(i.listWidth/i.options.slidesToShow),i.$slideTrack.width(Math.ceil(i.slideWidth*i.$slideTrack.children(".slick-slide").length))):i.options.variableWidth===!0?i.$slideTrack.width(5e3*i.slideCount):(i.slideWidth=Math.ceil(i.listWidth),i.$slideTrack.height(Math.ceil(i.$slides.first().outerHeight(!0)*i.$slideTrack.children(".slick-slide").length)));var e=i.$slides.first().outerWidth(!0)-i.$slides.first().width();i.options.variableWidth===!1&&i.$slideTrack.children(".slick-slide").width(i.slideWidth-e)},e.prototype.setFade=function(){var e,t=this;t.$slides.each(function(o,s){e=t.slideWidth*o*-1,t.options.rtl===!0?i(s).css({position:"relative",right:e,top:0,zIndex:t.options.zIndex-2,opacity:0}):i(s).css({position:"relative",left:e,top:0,zIndex:t.options.zIndex-2,opacity:0})}),t.$slides.eq(t.currentSlide).css({zIndex:t.options.zIndex-1,opacity:1})},e.prototype.setHeight=function(){var i=this;if(1===i.options.slidesToShow&&i.options.adaptiveHeight===!0&&i.options.vertical===!1){var e=i.$slides.eq(i.currentSlide).outerHeight(!0);i.$list.css("height",e)}},e.prototype.setOption=e.prototype.slickSetOption=function(){var e,t,o,s,n,r=this,l=!1;if("object"===i.type(arguments[0])?(o=arguments[0],l=arguments[1],n="multiple"):"string"===i.type(arguments[0])&&(o=arguments[0],s=arguments[1],l=arguments[2],"responsive"===arguments[0]&&"array"===i.type(arguments[1])?n="responsive":"undefined"!=typeof arguments[1]&&(n="single")),"single"===n)r.options[o]=s;else if("multiple"===n)i.each(o,function(i,e){r.options[i]=e});else if("responsive"===n)for(t in s)if("array"!==i.type(r.options.responsive))r.options.responsive=[s[t]];else{for(e=r.options.responsive.length-1;e>=0;)r.options.responsive[e].breakpoint===s[t].breakpoint&&r.options.responsive.splice(e,1),e--;r.options.responsive.push(s[t])}l&&(r.unload(),r.reinit())},e.prototype.setPosition=function(){var i=this;i.setDimensions(),i.setHeight(),i.options.fade===!1?i.setCSS(i.getLeft(i.currentSlide)):i.setFade(),i.$slider.trigger("setPosition",[i])},e.prototype.setProps=function(){var i=this,e=document.body.style;i.positionProp=i.options.vertical===!0?"top":"left",
"top"===i.positionProp?i.$slider.addClass("slick-vertical"):i.$slider.removeClass("slick-vertical"),void 0===e.WebkitTransition&&void 0===e.MozTransition&&void 0===e.msTransition||i.options.useCSS===!0&&(i.cssTransitions=!0),i.options.fade&&("number"==typeof i.options.zIndex?i.options.zIndex<3&&(i.options.zIndex=3):i.options.zIndex=i.defaults.zIndex),void 0!==e.OTransform&&(i.animType="OTransform",i.transformType="-o-transform",i.transitionType="OTransition",void 0===e.perspectiveProperty&&void 0===e.webkitPerspective&&(i.animType=!1)),void 0!==e.MozTransform&&(i.animType="MozTransform",i.transformType="-moz-transform",i.transitionType="MozTransition",void 0===e.perspectiveProperty&&void 0===e.MozPerspective&&(i.animType=!1)),void 0!==e.webkitTransform&&(i.animType="webkitTransform",i.transformType="-webkit-transform",i.transitionType="webkitTransition",void 0===e.perspectiveProperty&&void 0===e.webkitPerspective&&(i.animType=!1)),void 0!==e.msTransform&&(i.animType="msTransform",i.transformType="-ms-transform",i.transitionType="msTransition",void 0===e.msTransform&&(i.animType=!1)),void 0!==e.transform&&i.animType!==!1&&(i.animType="transform",i.transformType="transform",i.transitionType="transition"),i.transformsEnabled=i.options.useTransform&&null!==i.animType&&i.animType!==!1},e.prototype.setSlideClasses=function(i){var e,t,o,s,n=this;if(t=n.$slider.find(".slick-slide").removeClass("slick-active slick-center slick-current").attr("aria-hidden","true"),n.$slides.eq(i).addClass("slick-current"),n.options.centerMode===!0){var r=n.options.slidesToShow%2===0?1:0;e=Math.floor(n.options.slidesToShow/2),n.options.infinite===!0&&(i>=e&&i<=n.slideCount-1-e?n.$slides.slice(i-e+r,i+e+1).addClass("slick-active").attr("aria-hidden","false"):(o=n.options.slidesToShow+i,t.slice(o-e+1+r,o+e+2).addClass("slick-active").attr("aria-hidden","false")),0===i?t.eq(t.length-1-n.options.slidesToShow).addClass("slick-center"):i===n.slideCount-1&&t.eq(n.options.slidesToShow).addClass("slick-center")),n.$slides.eq(i).addClass("slick-center")}else i>=0&&i<=n.slideCount-n.options.slidesToShow?n.$slides.slice(i,i+n.options.slidesToShow).addClass("slick-active").attr("aria-hidden","false"):t.length<=n.options.slidesToShow?t.addClass("slick-active").attr("aria-hidden","false"):(s=n.slideCount%n.options.slidesToShow,o=n.options.infinite===!0?n.options.slidesToShow+i:i,n.options.slidesToShow==n.options.slidesToScroll&&n.slideCount-i<n.options.slidesToShow?t.slice(o-(n.options.slidesToShow-s),o+s).addClass("slick-active").attr("aria-hidden","false"):t.slice(o,o+n.options.slidesToShow).addClass("slick-active").attr("aria-hidden","false"));"ondemand"!==n.options.lazyLoad&&"anticipated"!==n.options.lazyLoad||n.lazyLoad()},e.prototype.setupInfinite=function(){var e,t,o,s=this;if(s.options.fade===!0&&(s.options.centerMode=!1),s.options.infinite===!0&&s.options.fade===!1&&(t=null,s.slideCount>s.options.slidesToShow)){for(o=s.options.centerMode===!0?s.options.slidesToShow+1:s.options.slidesToShow,e=s.slideCount;e>s.slideCount-o;e-=1)t=e-1,i(s.$slides[t]).clone(!0).attr("id","").attr("data-slick-index",t-s.slideCount).prependTo(s.$slideTrack).addClass("slick-cloned");for(e=0;e<o+s.slideCount;e+=1)t=e,i(s.$slides[t]).clone(!0).attr("id","").attr("data-slick-index",t+s.slideCount).appendTo(s.$slideTrack).addClass("slick-cloned");s.$slideTrack.find(".slick-cloned").find("[id]").each(function(){i(this).attr("id","")})}},e.prototype.interrupt=function(i){var e=this;i||e.autoPlay(),e.interrupted=i},e.prototype.selectHandler=function(e){var t=this,o=i(e.target).is(".slick-slide")?i(e.target):i(e.target).parents(".slick-slide"),s=parseInt(o.attr("data-slick-index"));return s||(s=0),t.slideCount<=t.options.slidesToShow?void t.slideHandler(s,!1,!0):void t.slideHandler(s)},e.prototype.slideHandler=function(i,e,t){var o,s,n,r,l,d=null,a=this;if(e=e||!1,!(a.animating===!0&&a.options.waitForAnimate===!0||a.options.fade===!0&&a.currentSlide===i))return e===!1&&a.asNavFor(i),o=i,d=a.getLeft(o),r=a.getLeft(a.currentSlide),a.currentLeft=null===a.swipeLeft?r:a.swipeLeft,a.options.infinite===!1&&a.options.centerMode===!1&&(i<0||i>a.getDotCount()*a.options.slidesToScroll)?void(a.options.fade===!1&&(o=a.currentSlide,t!==!0&&a.slideCount>a.options.slidesToShow?a.animateSlide(r,function(){a.postSlide(o)}):a.postSlide(o))):a.options.infinite===!1&&a.options.centerMode===!0&&(i<0||i>a.slideCount-a.options.slidesToScroll)?void(a.options.fade===!1&&(o=a.currentSlide,t!==!0&&a.slideCount>a.options.slidesToShow?a.animateSlide(r,function(){a.postSlide(o)}):a.postSlide(o))):(a.options.autoplay&&clearInterval(a.autoPlayTimer),s=o<0?a.slideCount%a.options.slidesToScroll!==0?a.slideCount-a.slideCount%a.options.slidesToScroll:a.slideCount+o:o>=a.slideCount?a.slideCount%a.options.slidesToScroll!==0?0:o-a.slideCount:o,a.animating=!0,a.$slider.trigger("beforeChange",[a,a.currentSlide,s]),n=a.currentSlide,a.currentSlide=s,a.setSlideClasses(a.currentSlide),a.options.asNavFor&&(l=a.getNavTarget(),l=l.slick("getSlick"),l.slideCount<=l.options.slidesToShow&&l.setSlideClasses(a.currentSlide)),a.updateDots(),a.updateArrows(),a.options.fade===!0?(t!==!0?(a.fadeSlideOut(n),a.fadeSlide(s,function(){a.postSlide(s)})):a.postSlide(s),void a.animateHeight()):void(t!==!0&&a.slideCount>a.options.slidesToShow?a.animateSlide(d,function(){a.postSlide(s)}):a.postSlide(s)))},e.prototype.startLoad=function(){var i=this;i.options.arrows===!0&&i.slideCount>i.options.slidesToShow&&(i.$prevArrow.hide(),i.$nextArrow.hide()),i.options.dots===!0&&i.slideCount>i.options.slidesToShow&&i.$dots.hide(),i.$slider.addClass("slick-loading")},e.prototype.swipeDirection=function(){var i,e,t,o,s=this;return i=s.touchObject.startX-s.touchObject.curX,e=s.touchObject.startY-s.touchObject.curY,t=Math.atan2(e,i),o=Math.round(180*t/Math.PI),o<0&&(o=360-Math.abs(o)),o<=45&&o>=0?s.options.rtl===!1?"left":"right":o<=360&&o>=315?s.options.rtl===!1?"left":"right":o>=135&&o<=225?s.options.rtl===!1?"right":"left":s.options.verticalSwiping===!0?o>=35&&o<=135?"down":"up":"vertical"},e.prototype.swipeEnd=function(i){var e,t,o=this;if(o.dragging=!1,o.swiping=!1,o.scrolling)return o.scrolling=!1,!1;if(o.interrupted=!1,o.shouldClick=!(o.touchObject.swipeLength>10),void 0===o.touchObject.curX)return!1;if(o.touchObject.edgeHit===!0&&o.$slider.trigger("edge",[o,o.swipeDirection()]),o.touchObject.swipeLength>=o.touchObject.minSwipe){switch(t=o.swipeDirection()){case"left":case"down":e=o.options.swipeToSlide?o.checkNavigable(o.currentSlide+o.getSlideCount()):o.currentSlide+o.getSlideCount(),o.currentDirection=0;break;case"right":case"up":e=o.options.swipeToSlide?o.checkNavigable(o.currentSlide-o.getSlideCount()):o.currentSlide-o.getSlideCount(),o.currentDirection=1}"vertical"!=t&&(o.slideHandler(e),o.touchObject={},o.$slider.trigger("swipe",[o,t]))}else o.touchObject.startX!==o.touchObject.curX&&(o.slideHandler(o.currentSlide),o.touchObject={})},e.prototype.swipeHandler=function(i){var e=this;if(!(e.options.swipe===!1||"ontouchend"in document&&e.options.swipe===!1||e.options.draggable===!1&&i.type.indexOf("mouse")!==-1))switch(e.touchObject.fingerCount=i.originalEvent&&void 0!==i.originalEvent.touches?i.originalEvent.touches.length:1,e.touchObject.minSwipe=e.listWidth/e.options.touchThreshold,e.options.verticalSwiping===!0&&(e.touchObject.minSwipe=e.listHeight/e.options.touchThreshold),i.data.action){case"start":e.swipeStart(i);break;case"move":e.swipeMove(i);break;case"end":e.swipeEnd(i)}},e.prototype.swipeMove=function(i){var e,t,o,s,n,r,l=this;return n=void 0!==i.originalEvent?i.originalEvent.touches:null,!(!l.dragging||l.scrolling||n&&1!==n.length)&&(e=l.getLeft(l.currentSlide),l.touchObject.curX=void 0!==n?n[0].pageX:i.clientX,l.touchObject.curY=void 0!==n?n[0].pageY:i.clientY,l.touchObject.swipeLength=Math.round(Math.sqrt(Math.pow(l.touchObject.curX-l.touchObject.startX,2))),r=Math.round(Math.sqrt(Math.pow(l.touchObject.curY-l.touchObject.startY,2))),!l.options.verticalSwiping&&!l.swiping&&r>4?(l.scrolling=!0,!1):(l.options.verticalSwiping===!0&&(l.touchObject.swipeLength=r),t=l.swipeDirection(),void 0!==i.originalEvent&&l.touchObject.swipeLength>4&&(l.swiping=!0,i.preventDefault()),s=(l.options.rtl===!1?1:-1)*(l.touchObject.curX>l.touchObject.startX?1:-1),l.options.verticalSwiping===!0&&(s=l.touchObject.curY>l.touchObject.startY?1:-1),o=l.touchObject.swipeLength,l.touchObject.edgeHit=!1,l.options.infinite===!1&&(0===l.currentSlide&&"right"===t||l.currentSlide>=l.getDotCount()&&"left"===t)&&(o=l.touchObject.swipeLength*l.options.edgeFriction,l.touchObject.edgeHit=!0),l.options.vertical===!1?l.swipeLeft=e+o*s:l.swipeLeft=e+o*(l.$list.height()/l.listWidth)*s,l.options.verticalSwiping===!0&&(l.swipeLeft=e+o*s),l.options.fade!==!0&&l.options.touchMove!==!1&&(l.animating===!0?(l.swipeLeft=null,!1):void l.setCSS(l.swipeLeft))))},e.prototype.swipeStart=function(i){var e,t=this;return t.interrupted=!0,1!==t.touchObject.fingerCount||t.slideCount<=t.options.slidesToShow?(t.touchObject={},!1):(void 0!==i.originalEvent&&void 0!==i.originalEvent.touches&&(e=i.originalEvent.touches[0]),t.touchObject.startX=t.touchObject.curX=void 0!==e?e.pageX:i.clientX,t.touchObject.startY=t.touchObject.curY=void 0!==e?e.pageY:i.clientY,void(t.dragging=!0))},e.prototype.unfilterSlides=e.prototype.slickUnfilter=function(){var i=this;null!==i.$slidesCache&&(i.unload(),i.$slideTrack.children(this.options.slide).detach(),i.$slidesCache.appendTo(i.$slideTrack),i.reinit())},e.prototype.unload=function(){var e=this;i(".slick-cloned",e.$slider).remove(),e.$dots&&e.$dots.remove(),e.$prevArrow&&e.htmlExpr.test(e.options.prevArrow)&&e.$prevArrow.remove(),e.$nextArrow&&e.htmlExpr.test(e.options.nextArrow)&&e.$nextArrow.remove(),e.$slides.removeClass("slick-slide slick-active slick-visible slick-current").attr("aria-hidden","true").css("width","")},e.prototype.unslick=function(i){var e=this;e.$slider.trigger("unslick",[e,i]),e.destroy()},e.prototype.updateArrows=function(){var i,e=this;i=Math.floor(e.options.slidesToShow/2),e.options.arrows===!0&&e.slideCount>e.options.slidesToShow&&!e.options.infinite&&(e.$prevArrow.removeClass("slick-disabled").attr("aria-disabled","false"),e.$nextArrow.removeClass("slick-disabled").attr("aria-disabled","false"),0===e.currentSlide?(e.$prevArrow.addClass("slick-disabled").attr("aria-disabled","true"),e.$nextArrow.removeClass("slick-disabled").attr("aria-disabled","false")):e.currentSlide>=e.slideCount-e.options.slidesToShow&&e.options.centerMode===!1?(e.$nextArrow.addClass("slick-disabled").attr("aria-disabled","true"),e.$prevArrow.removeClass("slick-disabled").attr("aria-disabled","false")):e.currentSlide>=e.slideCount-1&&e.options.centerMode===!0&&(e.$nextArrow.addClass("slick-disabled").attr("aria-disabled","true"),e.$prevArrow.removeClass("slick-disabled").attr("aria-disabled","false")))},e.prototype.updateDots=function(){var i=this;null!==i.$dots&&(i.$dots.find("li").removeClass("slick-active").end(),i.$dots.find("li").eq(Math.floor(i.currentSlide/i.options.slidesToScroll)).addClass("slick-active"))},e.prototype.visibility=function(){var i=this;i.options.autoplay&&(document[i.hidden]?i.interrupted=!0:i.interrupted=!1)},i.fn.slick=function(){var i,t,o=this,s=arguments[0],n=Array.prototype.slice.call(arguments,1),r=o.length;for(i=0;i<r;i++)if("object"==typeof s||"undefined"==typeof s?o[i].slick=new e(o[i],s):t=o[i].slick[s].apply(o[i].slick,n),"undefined"!=typeof t)return t;return o}});;



window.addEventListener('load', function () {
  /* take the parent element by class */
  let layoutRegion0 = document.getElementsByClassName("layout__region--first")[0];
  let layoutRegion01 = document.getElementsByClassName("layout__region--first")[1];
  let layoutRegion1 = document.getElementsByClassName("layout__region--second")[0];

  if(layoutRegion0 || layoutRegion1 || layoutRegion01) {

    let mpScreenshots = document.getElementById("mp_screenshots")
    let mp_screen_active = document.getElementById("mp_screen_active")
    // console.log(mp_screen_active)
    let mp_close = document.getElementsByClassName("mp_close")[0]
    // console.log(mp_close)

    let overlay = document.getElementById("overlay");
    // mp_screen_active.classList.add("active");

    /* add attr slide number */
    let sc_img = mpScreenshots.getElementsByTagName("img");
    // console.log(sc_img)
    for (let j = 0; j < sc_img.length; j++) {
      sc_img[j].setAttribute("data-slide-numb", [j])
      sc_img[j].addEventListener("click", function(e) {
        /* remove the triggering of any links */
        e.preventDefault();
        /* remove the scrolling for the body so that the content does not scroll */
        jQuery("body").css('overflow', 'hidden');
        /* darken the background */
        overlay.classList.add("popup_active");
        /* showing the block for the slider */
        mp_screen_active.classList.add("active");

        /* create a targeting or tracking area inside the block */
        let target = e.target;
        let slideNumb = target.getAttribute("data-slide-numb");
        /* set which screen the value was clicked on */
        startSlide(slideNumb);

        let clientHeight = document.documentElement.clientHeight
        let clientWidth = document.documentElement.clientWidth

        if(clientHeight < clientWidth) {
          imgHeight(clientHeight)
        }
        else {
          imgWidth(clientWidth)
        }
      });
    }


    jQuery('.mp_screen').slick({
      centerMode: true,
      variableWidth: true,
      infinite: false,
      speed: 0,
      slidesToShow: 1,
      slidesToScroll: 1,
    });
    function startSlide(slideNumb) {
      jQuery('.mp_screen').slick('slickGoTo',slideNumb);
    }


    function imgHeight(clientHeight) {
      let simgAll = jQuery('.mp_screen .slick-slide img');
      clientHeight = clientHeight - 128;
      for (let simg of simgAll) {
        simg.height = clientHeight
      }
    }


    function imgWidth(clientWidth) {
      let simgAll = jQuery('.mp_screen .slick-slide img');
      clientWidth = clientWidth - 32;
      // clientWidth = clientWidth - 96
      for (let simg of simgAll) {
        simg.width = clientWidth
      }
    }


    mp_close.addEventListener('click', function (event) {
      // let target = event.target
      if (mp_screen_active.classList.contains('active')) {
        jQuery("body").css('overflow', 'unset');
        overlay.classList.remove("popup_active");
        mp_screen_active.classList.remove("active");
      } else {
        mp_screen_active.classList.add("active");
      }
    }, false);
  }
});
;
var $ = jQuery;


/* Get corect URL dev or prod */
var urlchek = location.href;
if (urlchek.indexOf('sandbox.wamsco-cloud.net') + 1) {
  var devtoprod = 'sandboxr';
  var devtoprods = 'sandbox.';
  var intercom_id = 'a4rayasq';
  var centry_id = 'https://38c20dbcccd94907b95c398c2bf87401@sentry.io/2150980';
} else if (urlchek.indexOf('dev.wamsco-cloud.net') + 1) {
  var devtoprod = 'devr';
  var devtoprods = 'dev.';
  var intercom_id = 'a4rayasq';
  var centry_id = 'https://38c20dbcccd94907b95c398c2bf87401@sentry.io/2150980';
} else if (urlchek.indexOf('localhost') + 1) {
  var devtoprod = 'devr';
  var devtoprods = 'dev.';
  var intercom_id = 'a4rayasq';
  var centry_id = 'https://38c20dbcccd94907b95c398c2bf87401@sentry.io/2150980';
} else if (urlchek.indexOf('stage.wamsco-cloud.net') + 1) {
  var devtoprod = 'stager';
  var devtoprods = 'stage.';
  var intercom_id = 'f9zuf2ls';
  var centry_id = 'https://72a21ae9beb742cda9c1bd158b3aeb6d@o164757.ingest.sentry.io/5167064';
} else {
  var devtoprod = 'r';
  var devtoprods = '';
  var intercom_id = 're5afjv3';
  var centry_id = 'https://72a21ae9beb742cda9c1bd158b3aeb6d@o164757.ingest.sentry.io/5167064';
}


Sentry.init({
  dsn: centry_id,
  allowUrls: [
    /https?:\/\/((cdn|www)\.)?wamsco-cloud\.net/,
    /https?:\/\/((cdn|www)\.)?dev\.wamsco-cloud\.net/,
    /https?:\/\/((cdn|www)\.)?stage\.wamsco-cloud\.net/,
  ],
  ignoreErrors: [
    // Random plugins/extensions
    "top.GLOBALS",
    // See: http://blog.errorception.com/2012/03/tale-of-unfindable-js-error.html
    "originalCreateNotification",
    "canvas.contentDocument",
    "MyApp_RemoveAllHighlights",
    "http://tt.epicplay.com",
    "Can't find variable: ZiteReader",
    "jigsaw is not defined",
    "ComboSearch is not defined",
    "http://loading.retry.widdit.com/",
    "atomicFindClose",
    // Facebook borked
    "fb_xd_fragment",
    // ISP "optimizing" proxy - `Cache-Control: no-transform` seems to
    // reduce this. (thanks @acdha)
    // See http://stackoverflow.com/questions/4113268
    "bmi_SafeAddOnload",
    "EBCallBackMessageReceived",
    // See http://toolbar.conduit.com/Developer/HtmlAndGadget/Methods/JSInjection.aspx
    "conduitPage",
    // Now a lot of errors from plerdy are pouring inside their library.
    "plerdyReceiveMessage"
  ],
  denyUrls: [
    // Facebook flakiness
    /graph\.facebook\.com/i,
    // Facebook blocked
    /connect\.facebook\.net\/en_US\/all\.js/i,
    // Woopra flakiness
    /eatdifferent\.com\.woopra-ns\.com/i,
    /static\.woopra\.com\/js\/woopra\.js/i,
    // Chrome extensions
    /extensions\//i,
    /^chrome:\/\//i,
    // Other plugins
    /127\.0\.0\.1:4001\/isrunning/i, // Cacaoweb
    /webappstoolbarba\.texthelp\.com\//i,
    /metrics\.itunes\.apple\.com\.edgesuite\.net\//i,
  ],
});


/* get lang browser */
var lang = (navigator.language || navigator.browserLanguage).split('-');
lang = (lang [0]);

/* Get URL lang code */
var curentlangcode = window.location.pathname.split('/');
var arrlangcodenew = ["de", "vn", "es", "it", "fr", "pl", "ro", "ko", "jp", "ru", "cz", "ee", "idn", "ms", "ar", "th", "mx", "gr", "in", "zh", "ge", "sk", "nl", "bg", "br", "tr", "mn", "se", "no"];
var arrlangcodenewes = ["es", "es-pe"];
if (arrlangcodenew.indexOf(curentlangcode[1]) + 1) {
  curentlangcode = '/' + curentlangcode[1];
} else if (arrlangcodenewes.indexOf(curentlangcode[1]) + 1) {
  curentlangcode = '/es';
} else curentlangcode = "";

/* get County code */
var country = $("#country").attr('content');

/* Check login status */
$.ajax({
  type: 'GET',
  url: 'https://' + devtoprod + '.wamsco-cloud.net/data/iscabinetlogged',
  dataType: "json",
  crossDomain: true,
  xhrFields: {
    withCredentials: true
  },
  success: function (data) {
    console.log(data.status);
    console.log(data.liveChatAvailable);
    console.log(data.intercomSettings);
    var loginpage = document.getElementsByClassName('node-type-signup');
    console.log(loginpage.length);
    switch (data.status) {
      case true: // successful login
        $(".sign, .mob-sign, .signinfromsignup").attr("href", 'https://' + devtoprod + '.wamsco-cloud.net');
        if (window.location.pathname.indexOf('signup') + 1) {
          $(".signup-form").addClass("disableform");
          $(".signiup, .other-account").removeClass("disableform");
        }
        break;
      case false: // false login
        if (window.location.pathname.indexOf('signup') + 1) {
          $(".other-account").addClass("disableform");
          $(".signiup, .signup-form").removeClass("disableform");
        }
        if (loginpage.length < 1) {
          window.intercomSettings = {
            app_id: intercom_id,
            hide_default_launcher: false
          };
          loadchat();
        }
        break;
    }
    switch (data.liveChatAvailable) {
      case true: // successful chat
        if (loginpage.length < 1) {
          window.intercomSettings = {
            app_id: intercom_id,
            user_id: data.intercomSettings.ownerId,
            user_hash: data.intercomSettings.userHash,
            hide_default_launcher: false
          };
          loadchat();
        }
        break;
      case false: // false chat
        if (loginpage.length < 1) {
          loadchat();
          Intercom('shutdown');
        }
        break;
    }
  },
  error: function (jqXHR, textStatus, err) {
    Sentry.captureException(err);
    $(".other-account").addClass("disableform");
    $(".signiup, .signup-form").removeClass("disableform");
  }
});


/* loadin chat */
function loadchat() {
  (function () {
    var w = window;
    var ic = w.Intercom;
    if (typeof ic === "function") {
      ic('reattach_activator');
      ic('update', w.intercomSettings);
    } else {
      var d = document;
      var i = function () {
        i.c(arguments);
      };
      i.q = [];
      i.c = function (args) {
        i.q.push(args);
      };
      w.Intercom = i;
      var l = function () {
        var s = d.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = 'https://widget.intercom.io/widget/re5afjv3';
        var x = d.getElementsByTagName('script')[0];
        s.onload = function () {
          w.Intercom('boot', w.intercomSettings);
        }
        x.parentNode.insertBefore(s, x);
      };
      if (w.attachEvent) {
        w.attachEvent('onload', l);
      } else {
        w.addEventListener('load', l, false);
      }
    }
  })();
}

;






var autoscrollTimer = 0;
function log(str, no_br) {
  var br = '\n';
  if (no_br) br = '';
  var log = 0;
  // var log = document.getElementById('outData');
  var new_val = '';
  if (typeof str !== 'undefined') new_val = log.value + str+br;
  log.value = new_val;
  clearTimeout(autoscrollTimer); // cancel previous timer
  autoscrollTimer = setTimeout(function() {
    log.scrollTop = log.scrollHeight; // autoscroll log
  }, 200);
}

function getById(identifier) {
  return document.getElementById(identifier);
}

(function initOnLoad(onLoadFunc) {
  if (window.addEventListener){ window.addEventListener("load", onLoadFunc, false); }
  else if (window.attachEvent) { window.attachEvent("onload", onLoadFunc); }
  else { document.addEventListener("load", onLoadFunc, false);}
})(function () {
  log('Page loaded');
});

function loginToDemoAccount() {
  // var lang = "eng"; /*this is lang: "eng" cabinetLang: "eng"  country: "us"*/
  // var lang = "spa"; /*this is lang: "eng" cabinetLang: "spa"  country: "us"*/
  // var lang = "rus"; /*this is lang: "eng" cabinetLang: "rus"  country: "us"*/
  // var lang = "fra";
  // var lang = (navigator.language||navigator.browserLanguage).split('-');
  // lang = (lang [0]);
  /* get lang browser*/
  var curentlangcode = window.location.pathname.split('/');
  var array = {
    'en-id': 'eng', 'en-ph': 'eng', 'en-au': 'eng', 'en-in': 'eng','en-my': 'eng',
    'en': 'eng', 'sk': 'slk', 'es': 'spa', 'es-pe': 'spa', 'mx': 'spa', 'de': 'deu',
    'it': 'ita', 'fr': 'fra', 'pl': 'pol', 'ro': 'ron', 'ko': 'kor', 'jp': 'jpn',
    'cn': 'zho', 'gr': 'ell', 'ru': 'rus', 'bg': 'bul', 'br': 'bre', 'vn': 'vie',
    'ee': 'est', 'nl': 'nld', 'al': 'sqi', 'se': 'swe', 'mn': 'mon', 'dk': 'dan',
    'hu': 'hun', 'idn': 'ind', 'cz': 'ces', 'no': 'nor', 'tr': 'tur', 'in': 'hin',
    'th': 'tha', 'ms': 'msa', 'ar': 'ara', 'ur': 'urd'};
  if(array[curentlangcode[1]] !== undefined) {
    var lang = array[curentlangcode[1]];
  } else {
    lang = 'eng';
  }
  var req = new XMLHttpRequest();
  var requestText = JSON.stringify({
    "lang": lang, //cabinet lang === lang browser
    "captchaResponse": captchaResponse
  });
  req.withCredentials = true;
  // log("Send: "+requestText);
  req.onload = function() {
    // log("Response: "+this.responseText);
    if (captchaResponse !== "") {
      window.open("https://devr.wamsco-cloud.net/dashboard/#/report/sales");
    }
    return captchaResponse = "";
  };
  req.open("post", "https://devr.wamsco-cloud.net/data/cabinetdemologin");
  // req.open("post", "/data/cabinetdemologin", true);
  req.send(requestText);
}


var captcha;
var captchaResponse = "";
var captchaCallback = function(response) {
  captchaResponse = response;
  log(response);
};
var onloadCallback = function() {
  // https://developers.google.com/recaptcha/docs/display
  captcha = grecaptcha.render('captchaContainer', {
    'sitekey' : '6Lc5I6YUAAAAAAbtpKBX9XK6zEk14wToFRvbcpkB',
    'callback' : captchaCallback,
    'theme' : 'light'
  });
};
function reloadCaptcha() {
  captchaResponse = "";
  grecaptcha.reset(captcha);
}

;
WebFont.load({
  google: {
    families: ['Noto Sans']
  }
});
;
window.addEventListener('load', function () {

  /*  LINKS BY CLICK Card payment  */
  const products = document.querySelectorAll(".card-payment-link")

  function moveOnLink(e) {
    e.preventDefault();
    window.open(this.dataset.href,"_self")
  }

  products.forEach(div => div.addEventListener('click', moveOnLink))
  /*  END LINKS BY CLICK Card payment  */
});
;
//
//
// window.addEventListener('load', function () {
//   let video = document.getElementById("vidos");
//   let pop_up = document.getElementById("video_block");
//   let popup_closer = pop_up.querySelector(".popup_wrapper .mobile-arrow");
//   let popup_cont = pop_up.getElementsByTagName("iframe")[0];
//   let overlay = document.getElementById("overlay");
//
//   if (video) {
//     video.addEventListener('click', function (e) {
//       /* cancel the launch of the video by clicking on the link */
//       e.preventDefault();
//       /* remove the scrolling for the body so that the content does not scroll */
//       jQuery("body").css('overflow', 'hidden');
//       /* showing the video block */
//       pop_up.classList.add("active");
//       /* darken the background */
//       overlay.classList.add("popup_active");
//       /* prescribe which link to take */
//       popup_cont.setAttribute("src", video.getAttribute("href"));
//       /* calculate the dimensions of the screen and minus the indents around the perimeter 25% */
//       let w = (document.documentElement.clientWidth * 0.75);
//       let h = (document.documentElement.clientHeight * 0.75);
//       let style = "width: " + w + "px; height: " + h + "px";
//       popup_cont.setAttribute("style", style);
//     }, false);
//   }
//
//   /* in the window that opens, listen to clicking on the close icon */
//   popup_closer.addEventListener('click', function () {
//     jQuery("body").css('overflow', 'unset');
//     overlay.classList.remove("popup_active");
//     if (pop_up.classList.contains('active')) {
//       pop_up.classList.remove("active");
//       popup_cont.setAttribute("src", "");
//     }
//     else {
//       pop_up.classList.add("active");
//     }
//   }, false);
// });
;
