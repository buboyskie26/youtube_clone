//

function postComment(button, postedBy, videoId, replyTo, containerClass) {
  //
  var textarea = $(button).siblings('textarea');
  var commentText = textarea.val();

  textarea.val('');
  //
  if (commentText) {
    $.post('ajax/postComment.php', {
      commentText,
      postedBy,
      videoId,
      responseTo: replyTo,
    }).done(function (comment) {
      //

      // var commentParse = JSON.parse(comment);
      // console.log(commentParse.replies);

      var parent = $(button).closest('.itemContainer');
      var sib = parent.find('.replyCount').first();

      // var replyCount = sib.text();
      // var heu = sib.text(parseInt(replyCount) + parseInt(1));
      updateLikesValue(sib, 1);
      if (replyTo == null) {
        // CommentSection.php Line49 class='comments' placed on the class
        $('.' + containerClass).prepend(comment); // containerClass = comments class
      } else if (replyTo != null) {
        $(button)
          .parent() // .itemContainer
          .siblings('.' + containerClass) // containerClass = .repliesSection
          .append(comment);
      }
    });
  } else {
    alert("You can't post an empty comment");
  }
}

function likeComment(commentId, button, videoId) {
  $.post('ajax/likeComment.php', { commentId, videoId }).done(function (
    numToChange
  ) {
    // console.log(results);

    var likeButton = $(button);
    var dislikeButton = $(button).siblings('.dislikeButton');

    likeButton.addClass('active');
    dislikeButton.removeClass('active');

    var likesCount = $(button).siblings('.likesCount');
    updateLikesValue(likesCount, numToChange);

    if (numToChange < 0) {
      likeButton.removeClass('active');
      likeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-up.png');
    } else {
      likeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-up-active.png');
    }

    dislikeButton
      .find('img:first')
      .attr('src', 'assets/images/icons/thumb-down.png');
  });
}
function dislikeComment(commentId, button, videoId) {
  $.post('ajax/dislikeComment.php', { commentId, videoId }).done(function (
    numToChange
  ) {
    //
    var dislikeButton = $(button);
    var likeButton = $(button).siblings('.likeButton');

    dislikeButton.addClass('active');
    likeButton.removeClass('active');

    var likesCount = $(button).siblings('.likesCount');
    updateLikesValue(likesCount, numToChange);

    if (numToChange == 1) {
      dislikeButton.removeClass('active');
      dislikeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-down.png');
    } else if (numToChange < 0) {
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
  var likesCountVal = element.text() || 0;
  element.text(parseInt(likesCountVal) + parseInt(num));
}

function getReplies(commentId, button, videoId) {
  $.post('ajax/getCommentReplies.php', { commentId, videoId }).done(function (
    replyObject
  ) {
    // var replies = $('<div>').addClass('repliesSection');

    // // var str =
    // //   '<span class="repliesSection viewReplies" onclick="getReplies($commentId, this, $videoId)"> View All 1 replies </span>';
    // // replies.append(str);

    // replies.append(replyObject);
    // // replace the button clicked into
    // //  replies div.repliesSection with replies replyObject div
    // $(button).replaceWith(replies);

    // var replyCount = $(button).find('.replyCount');
    // updateLikesValue(replyCount, replyObject);

    var parent = $(button).closest('.itemContainer');

    var replyForm = parent.find('.repliesSection').first();
    replyForm.toggleClass('hidden');
    // console.log(replyObject);
    //
  });
}

function toggleReply(button) {
  var parent = $(button).closest('.itemContainer');
  var commentForm = parent.find('.commentForm').first();
  commentForm.toggleClass('hidden');
}

// $(document).ready(function(button) {

//   var replyCount = $(button).find('.replyCount');
//   updateLikesValue(replyCount, replyObject);
// })
