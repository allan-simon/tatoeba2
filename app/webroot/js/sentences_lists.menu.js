/*
    Tatoeba Project, free collaborative creation of multilingual corpuses project
    Copyright (C) 2009  HO Ngoc Phuong Trang (tranglich@gmail.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$(document).ready(function() {
	$(".addToListButton").click(function(){
		
		var sentence_id = $(this).parent().parent().attr("id");
		var list_id = $(".listSelection"+sentence_id).val();
		
		$("#favorite_"+sentence_id+"_in_process").show();
		
		$.post("http://" + self.location.hostname + "/sentences_lists/add_sentence_to_list/"+ sentence_id + "/" + list_id
			, {}
			,function(data){
				$("#favorite_"+sentence_id+"_in_process").hide();
			}
		);
	});
});