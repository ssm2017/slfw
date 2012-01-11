// put your own drupal installation url like : http://mysite.com or http://mysite.com/drupal
string url = "";
// choose the way to display the result
string outputType = "message";
// change the time between requests
float requestTimer = 2;
// list that contains commands to ask
list commands = ["hello", "datetime", "dump", "wrong_command" ];
// add arguments
string args = "argument1=a:argument2=b:argument3=c";
// ==================================================
//      NOTHING SHOULD BE CHANGED UNDER THIS LINE
// ==================================================
integer commandsCount = 0;
integer counter = 0;
string url2 = "/secondlife";
key goId;
go(string cmd)
{
    goId = llHTTPRequest( url+url2, [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                "app=sltest"
                +"&cmd="+cmd
                +"&output_type="+outputType
                +"&arg="+args
                );
}
default
{
    state_entry()
    {
        commandsCount = llGetListLength(commands);
        llSetTimerEvent(requestTimer);
    }

    http_response(key request_id, integer status, list metadata, string body)
    {
        body = llStringTrim( body , STRING_TRIM);
        if ( request_id == goId )
        {
            list values = llParseStringKeepNulls(body,[";"],[]);
            string msg = llList2String(values, 0);
            string value = llList2String(values, 1);
            if ( msg == "success" )
            {
                llOwnerSay(value);
            }
            else
            {
                llOwnerSay(body);
            }
        }
            
    }
    timer()
    {
        if ( counter < commandsCount )
        {
            go(llList2String(commands,counter));
            ++counter;
        }
        else
        {
            llSetTimerEvent(0);
        }
    }
}
