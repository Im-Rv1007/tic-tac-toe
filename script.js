fetchResults();

player_move = "";

function markCell(cell_no){
    var points_table_id = $("#pointTableId").val();
    $.ajax({
        url:'routes.php?action=mark_cell',
        method:'POST',
        data:{cell_no:cell_no,points_table_id:points_table_id,player_move:player_move},
        dataType:'json',
        success:function(resp){
            console.log(resp);
            $('#b'+resp['cell_no']).attr('disabled',true);
            $('#b'+resp['cell_no']).attr('cursor','none');

            $("#pointTableId").val(resp['points_table_id']);
            
            if((resp['winner'] != '') || (resp['draw'] != 0)){
                if(resp['winner'] != ''){
                    alert("Winner Is "+player_move);
                    fetchResults();
                }
                if(resp['draw'] != 0){
                    alert("Match Draw");
                    fetchResults();
                }

                $('.board_cell').attr('disabled',true);
                $('.board_cell').attr('cursor','none');
                
                for(var i=1;i<10;i++){
                    $('#b'+i).attr('pointer-events','none');
                }
            }

            if(player_move == 'X'){
                player_move = 'O';
                $('#b'+resp['cell_no']).html('X');
                $("#player_turn").html('O');
            }else{
                player_move = 'X';
                $('#b'+resp['cell_no']).html('O');
                $("#player_turn").html('X');
            }
        }
    });
}


function fetchResults(){
    $.ajax({
        url:'routes.php?action=fetch_results',
        method:'POST',
        dataType:'json',
        success:function(resp){
            player_move = resp.player_move;
            $("#player_turn").html(resp.player_move);
            $("#past_match").html(resp.past_match);
            $("#score_card").html(resp.points_table);
        }
    });
}

function resetCell(){
    // $.ajax({
    //     url:'routes.php?action=reset_cell',
    //     method:'POST',
    //     dataType:'json',
    //     success:function(resp){
            fetchResults();
            $("#pointTableId").val(0);
            for(var i=1;i<10;i++){
                $('#b'+i).html('');
                $('#b'+i).attr('disabled',false);
                $('#b'+i).attr('cursor','pointer');
            }
    //     }
    // });
}