//Toggle active inputs for more intuitive user experience
function toggleInputs(){
  if(!$('form').hasClass('out')){
    $('#lineSelect').removeClass('inactive');
    if($('#line').val() != null && $('#line').val() != ''){
      $('#scheduleType').removeClass('inactive');
      if($('#days').val() != null && $('#days').val() != ''){
        $('#trip').removeClass('inactive');
        if($('#origin').val() != null && $('#origin').val() != '' && $('#destination').val() != null && $('#destination').val() != ''){
          $('#submitter').removeClass('inactive');
        }
        else $('#submitter').addClass('inactive');
      }
      else $('#trip, #submitter').addClass('inactive');
    }
    else{
      $('#scheduleType, #trip, #submitter').addClass('inactive');
    }
  }
}

//Get station list based on line dropdown
function checkLine(){
  let myOption = $('#line option:selected').val();
  let stationList = $('#stations').find('[data-line="' + myOption + '"]').html();
  $('#origin, #destination').html(stationList);
}
function checkFeed(){
  if($('.scheduleWrap').hasClass('in')){
    $.ajax({
      url: 'api/trainview.php',
      type: 'GET',
      success: function(response){
        var liveFeed = JSON.parse(response);
        for(var i = 0; i < liveFeed.length; i++){
          let myTrain = liveFeed[i]['trainno'];
          if($('.scheduleWrap.in .trainWrap[data-train="'+myTrain+'"]').length){
            if($('select[data-line="'+$('.trainBlockWrap').attr('data-line')+'"] option:contains("'+liveFeed[i]['nextstop']+'")').length){
              $('.scheduleWrap.in .trainWrap[data-train="'+myTrain+'"]').addClass('running');
              $('.scheduleWrap.in .trainWrap[data-train="'+myTrain+'"] .current').html('&nbsp;'+liveFeed[i]['nextstop'] + ' next');
              if(liveFeed[i]['late'] == 0){
                $('.scheduleWrap.in .trainWrap[data-train="'+myTrain+'"] .delay').text('On time');
              }
              else if(liveFeed[i]['late'] == 1){
                $('.scheduleWrap.in .trainWrap[data-train="'+myTrain+'"] .delay').text(liveFeed[i]['late']+' min');
              }
              else{
                if(liveFeed[i]['late'] >= 3 && liveFeed[i]['late'] < 10){
                  $('.scheduleWrap.in .trainWrap[data-train="'+myTrain+'"]').addClass('slow');
                }
                else if(liveFeed[i]['late'] >= 10){
                  $('.scheduleWrap.in .trainWrap[data-train="'+myTrain+'"]').addClass('late');
                }
                $('.scheduleWrap.in .trainWrap[data-train="'+myTrain+'"] .delay').text(liveFeed[i]['late']+' mins');
              }
            }
          }
        }
      }
    });
  }
}

//Initialize
$(document).ready(function(){
  checkFeed();
  let inputCheck = setInterval(function(){
    toggleInputs();
  }, 100);
  checkLine();

  let feedCheck = setInterval(function(){
    checkFeed();
  }, 5000);
})

//Line dropdown change binding
$(document).delegate('#line', 'change', function(e){
  checkLine();
});
//Form submission action
$(document).delegate('form input[type="submit"]', 'click', function(e){
  e.preventDefault();
  if($('#line').val() === null || $('#schedule').val() === null || $('#origin').val() === null || $('#destination').val() === null){
    alert('Please fill in all fields');
  }
  else{
    let myQuery = $('form').serialize();
    window.history.pushState('', '', '?' + myQuery);
    $.ajax({
      url: $('form').attr('action'),
      type: 'GET',
      data: myQuery,
      success: function(response){
        $('.scheduleWrap').html(response).addClass('in');
        $('form').addClass('out');
        $('#back').addClass('in');
        checkFeed();
      }
    });
  }
});
//Back to form
$(document).delegate('#back', 'click', function(e){
  e.preventDefault();
  $(this).removeClass('in');
  $('form').removeClass('out');
  window.history.pushState('', '', '?');
  $('body, html').animate({
    scrollTop: 0
  }, 500);
  $('.scheduleWrap').animate({
    top: '200%'
  }, 500, function(){
    $('.scheduleWrap').removeClass('in').removeAttr('style');
  });
});
