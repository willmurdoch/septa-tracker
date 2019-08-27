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

//Initialize
$(document).ready(function(){
  let inputCheck = setInterval(function(){
    toggleInputs();
  }, 100);
  checkLine();
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
