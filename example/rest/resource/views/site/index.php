<?php

 
?>

<html><head><title>REST</title></head><body>
       
   <h1>Hello REST!</h1>
        <p><a data-method="GET" id="user-all" href="/user" >Show all users</a></p>
        <p><a data-method="GET" id="user-one" href="/user/1" >Show one user by ID</a></p>
        <p><a data-method="DELETE" id="user-remove" href="/user/1" >Remove one user by ID</a></p>
        <p><a data-method="POST" id="user-add" href="/user" >Add new user</a></p>
        <p><a data-method="PUT" id="user-update" href="/user/1">Update user by ID</a></p>
        
        <textarea id="user-result" cols="100" rows="20">Try click to any link ...</textarea>
        
        
        <script type="text/javascript">
        (function(d){
            
            var 
                userResult = d.getElementById('user-result'),
                links = ['user-one', 'user-all', 'user-remove', 'user-add', 'user-update'],
                key, xhr
            ;    
            
            for(key in links) { 
                
                d.getElementById(links[key])
                    .addEventListener('click', function(e){
                    
                    e.stopPropagation();
                    e.preventDefault();
                    send(this.href, this.getAttribute('data-method')); 
                    
                },false);
            }
                
                
            
            function send(url, method) {
                
                userResult.innerHTML = 'Send query '  + method + ' - ' + url + ' ...\n';
                
                if(!xhr) {
                   xhr = new XMLHttpRequest(); 
                }
                 
                xhr.open(method, url, false);
                xhr.send();
                
                userResult.innerHTML += 'Status: ' + xhr.status + ', StatusText: ' + xhr.statusText + ' ...\n';
                if (xhr.status === 200) {  
                    userResult.innerHTML += 'responseText:\n' + xhr.responseText  + '\n\n';
                }
                
                userResult.innerHTML += 'Done ...\n';
                
            }
            
            
            
        })(document);
        
        
        </script>
        
   </body></html>