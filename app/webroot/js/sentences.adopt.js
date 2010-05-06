/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2010  Allan SIMON <allan.simon@supinfo.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

$(document).ready(function(){

    var host = self.location.hostname;
    var port = self.location.port;

    $(".adopt").click(function(){
        var adoptOption = $(this);
        var sentenceId = $(this).data("sentenceId");
        
        // Displaying loading gif
        $("#sentences_group_" + sentenceId).html(
            "<img src='/img/loading.gif' alt='loading'>"
        );
        
        // The sentence can be adopted
        if (adoptOption.hasClass("add")){
            $("#sentences_group_" + sentenceId).load(
                "http://" + host + ":" + port + "/sentences/adopt/"+  sentenceId
            );
        }
        
        // The sentence can be unadopted 
        else if (adoptOption.hasClass("remove")){
            $("#sentences_group_" + sentenceId).load(
                "http://" + host + ":" + port + "/sentences/let_go/"+  sentenceId
            );
        }
    });
});

