$(document).ready(function () {
  $('.navShowHide').on('click', function () {
    main = $('#mainSectionContainer');
    nav = $('#sideNavContainer');

    if (main.hasClass('leftPadding')) {
      nav.hide();
    } else {
      nav.show();
    }

    main.toggleClass('leftPadding');
  });
});


