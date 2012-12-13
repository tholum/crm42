<script>

slimcrm.twitter = {
    tweets : {},
    update_tweet: function(id,result){
        twitter.update_tweet( slimcrm.twitter.tweets[id] , result , $('.twitter_search').val() , {});
        if( result == 1 ){
            $('.twitter_down.twitter_' + id ).css('background', 'grey');
        } else {
            $('.twitter_up.twitter_' + id ).css('background', 'grey');
        }
    },
    search: function( data){
        $('.twitter_results').html('');
        slimcrm.twitter.data = data;
        $.each(data.results , function(key, val) {
            if( slimcrm.twitter.tweets[val.id] == undefined ){
                if( slimcrm.temp == 0 ){
                    slimcrm.temp == 1;
                } else {
                    slimcrm.temp = 0;
                }
                $('.twitter_results').append('<div class="twitter_message" ><div class="twitter_user" >' + val.from_user+ '</div><div class="twitter_up twiter_icon twitter_' + val.id + '" onclick="slimcrm.twitter.update_tweet(' + val.id + ' , 1 );" >&nbsp;</div><div class="twitter_down twiter_icon twitter_' + val.id + '" onclick="slimcrm.twitter.update_tweet(' + val.id + ' , 0 );" >&nbsp;</div><div class="twitter_text" style="" >' + val.text + '</div></div>');   
                slimcrm.twitter.tweets[val.id] = val;
            }
        } );
    }
    
};

//function(data){ slimcrm.testdata=data; alert('test'); }
</script>
<div class="twitter_container">
<input  class="twitter_search"onchange="$.getJSON('twitter.php?q=' + encodeURI($(this).val()) , slimcrm.twitter.search )" style="width: 500px;" />
<div class="twitter_results"></div>
</div>