//
//

function likeVideo(button, videoId) {
  //
  $.post('ajax/likeVideo.php', { videoId }).done(function (data) {
    //
    var likeButton = $(button);
    var dislikeButton = $(button).siblings('.dislikeButton');

    likeButton.addClass('active');
    dislikeButton.removeClass('active');

    var results = JSON.parse(data);

    updateLikesValue(likeButton.find('.text'), results.likes);
    updateLikesValue(dislikeButton.find('.text'), results.dislikes);

    // results.likes possible output either 1, -1;
    if (true) {
      if (results.likes == -1) {
        likeButton.removeClass('active');
        // Find Image src and change it to thumb-up.png (default one)
        likeButton
          .find('img:first')
          .attr('src', 'assets/images/icons/thumb-up.png');
      } else if (results.likes == 1) {
        // Find Image src and change it to thumb-up-active.png (if liked)
        likeButton
          .find('img:first')
          .attr('src', 'assets/images/icons/thumb-up-active.png');
      }
    }

    // In every like, we need to set thumb-down.png as default.
    dislikeButton
      .find('img:first')
      .attr('src', 'assets/images/icons/thumb-down.png');
  });
}

function dislikeVideo(button, videoId) {
  $.post('ajax/dislikeVideo.php', { videoId }).done(function (data) {
    var dislikeButton = $(button);
    var likeButton = $(button).siblings('.likeButton');

    dislikeButton.addClass('active');
    likeButton.removeClass('active');

    var results = JSON.parse(data);

    updateLikesValue(likeButton.find('.text'), results.likes);
    updateLikesValue(dislikeButton.find('.text'), results.dislikes);

    if (results.dislikes < 0) {
      dislikeButton.removeClass('active');

      dislikeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-down.png');
    } else {
      dislikeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-down-active.png');
    }
    likeButton
      .find('img:first')
      .attr('src', 'assets/images/icons/thumb-up.png');
  });
}

function updateLikesValue(element, num) {
  //
  var likeCountValue = element.text() || 0;
  element.text(parseInt(likeCountValue) + parseInt(num));
}

function likedVideo(button, videoId) {
  $.post('ajax/likedVideo.php', { videoId }).done(function (data) {
    // console.log(results);

    var likeButton = $(button);
    var dislikeButton = $(button).siblings('.dislikeButton');

    likeButton.addClass('active');
    dislikeButton.removeClass('active');

    var results = JSON.parse(data);

    updateValue(likeButton.find('.text'), results.likes);
    updateValue(dislikeButton.find('.text'), results.dislikes);
    
    console.log(results.likes);
    if (results.likes < 0) {
      likeButton.removeClass('active');
      likeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-up.png');
    } else if (results.likes == 1) {
      likeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-up-active.png');
    }

    dislikeButton
      .find('img:first')
      .attr('src', 'assets/images/icons/thumb-down.png');
  });
}

function updateValue(buttonElement, number) {
  var likeCounts = $(buttonElement).text() || 0;
  buttonElement.text(parseInt(likeCounts) + parseInt(number));
}
