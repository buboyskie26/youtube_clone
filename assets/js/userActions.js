//

function subscribes(userTo, userFrom, button) {
  if (userTo == userFrom) {
    alert('You should not subscribed yourself');
    return;
  }

  $.post('ajax/subscribe.php', { userTo, userFrom }).done(function (count) {
    if (count != null) {
      $(button).toggleClass('subscribe unsubscribe');
      //   $(button).toggleClass('first second');

      var buttonNext = $(button).hasClass('subscribe')
        ? 'SUBSCRIBE'
        : 'SUBSCRIBED';

      $(button).text(buttonNext + ' ' + count);
    } else {
      alert('Something went wrong, userActions line19');
    }
  });
}

function subscribex(userTo, userFrom, button) {
  $.post('ajax/subscribex.php', { userTo, userFrom }).done(function (data) {
    if (data != null) {
      // Tandem to do the changing class
      //  $buttonClass = $isSubscribedTo ? "unsubscribe button" : "subscribe button"; ButtonProvider.php L81
      $(button).toggleClass('unsubscribe subscribe');

      var buttonText = $(button).hasClass('subscribe')
        ? 'SUBSCRIBE'
        : 'SUBSCRIBED';

      $(button).text(buttonText + ' ' + data);
    }
  });
}
