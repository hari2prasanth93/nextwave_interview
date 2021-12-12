Steps to run this project
1. Assigned the DB name to .env file
2. Run the database migration - php artisan migrate
3. Project URLS listed below:  
4. Device Register - http://localhost/example_lar/public/deviceRegister - post method
     Request - post data as json - {
	      "device_id":"2001244",
	      "version_number":2,
	      "platform":"android"
      }
     Response - jwt token(device ID)

5. User Register - http://localhost/example_lar/public/userRegister - post method
   header - credentials - jwt token(device ID)
   Request - post data as json -
    {
	"unique_id":"test24355",
	"username":"hari"
    }

    Response - user jwt token(user_id,device_id)

6. Login - http://localhost/example_lar/public/login - post method
   header - credentials - jwt token(device id)
   Request - post data as json -
    {
	"unique_id":"test24355"
    }
     
   Response - user_jwt(user_id,device_id),user_info(user table)

7. TournamentLists - http://localhost/example_lar/public/tournamentLists - get method
   header - credentials - jwt token(user_jwt token(user_id,device_id))
   Response - Based on the device(get it from token) platform it will display the tournament  
        
8. Add Tournament Score - http://localhost/example_lar/public/addTournamentScore/1 - post method
   param - TournamentID
   header - credentials - jwt token(user_jwt token(user_id,device_id))
   Request - post data as json -
   {
     "score" : 30
   }
   Response - Added Score will display 

9. Schedule cron for ranking - app/console/kernel.php - php artisan schedule:run
    
10. Leader Board - http://localhost/example_lar/public/leaderBoard/1 - Get Method
   Param - TournamentID
   header - credentials - jwt token(user_jwt token(user_id,device_id))
   Response - Top 10 rank and logged In user rank  
    

 