var mysql 		= require('mysql');
var md5 		= require('crypto-js/md5');
var moment 		= require('moment');
var created 	= new Date();
var updated 	= new Date();
var connection 	= mysql.createConnection({
  host     : '10.107.1.23',
  user     : 'DSP-dev',
  password : 'dspcoreDevops@1',
  database : 'ticketadmin'
});
connection.connect();
module.exports = function(app, passport) {
	//==================================
	//	GET METHODS
	//==================================
	app.get('/', function(req, res) {
		res.render('index.ejs');
	});
	app.get('/login', function(req, res) {
		res.render('login.ejs');
	});
	app.get('/signup', function(req, res) {
		res.render('signup.ejs');
	});
	app.get('/logout', function(req,res) {
		req.logout();
		res.redirect('/');
	});
	app.get('/users', function(req, res) {
		connection.query('SELECT * from users', function(err,rows,fields){
			if(!err) {res.json(rows);} else {res.send(err);}
		});
	});
	app.get('/access-type', function(req, res) {
		connection.query('SELECT * from access_type', function(err,rows,fields){
			if (!err) {res.json(rows);} else {res.send(err);}
		});
	});
	//=================================
	//	POST METHODS
	//=================================
	app.post('/signup', function(req,res) {
		var email = req.body.email;
		var firstname = req.body.first_name;
		var lastname = req.body.last_name;
		var password = req.body.password;
		var hashpass = md5(password);
		var user = {
			first_name: firstname, 
			last_name: lastname, 
			email: email, 
			password: hashpass, 
			date_created: created,
			date_updated: updated
		};
		connection.query('SELECT * FROM users WHERE email = ?',[email], function(err,rows,fields) {
			if(!err) {
				if(rows.length) {
					res.send({
						"code": 202,
						"success": "Email is already taken"
					})
				}
				else 
				{
					connection.query('INSERT INTO users SET ?',user, function(err, rows, fields) {
						if(!err) {
							res.send({
								"code": 201,
								"success": "User Registered"
							});
						} else {
							res.send(err);
						}
					});
					var action = "User " + firstname + " " + lastname + " created ";
					var type = "User";
					logAction(type,action); 
				}
			}
			else { res.send({"code":500, "error":err})}
		});
	});

	// process the login form
    // app.post('/login', passport.authenticate('local-login', {
    //     successRedirect : '/profile', // redirect to the secure profile section
    //     failureRedirect : '/login', // redirect back to the signup page if there is an error
    //     failureFlash : true // allow flash messages
    // }));
    
	app.post('/login', function(req, res) {
		var email = req.body.email;
		var password = req.body.password;
		var hashpass = md5(password);
		connection.query('SELECT * from users WHERE email = ?',[email], function(err,rows,fields){
			if(!err) 
			{
				if(rows.length > 0){
					if([0].password === password)
					{
						res.send({
				        	"code":201,
				        	"success":"Login Successful"
			        	});
					}
					else
					{
						res.send({
				        	"code":202,
				        	"success":"Email and Password does not match"
			        	});
					}
				}
				else
				{
					res.send({
				        "code":203,
				        "success":"Email does not exist"
			        });
				}
			}
			else
			{
				res.send(err);
			}
		});
	});
};

function isLoggedIn(req, res, next) {
	if(req.isAuthenticated()) {
		return next();
	} 
	else
	{
		res.redirect('/');
	}
}

function logAction(type,action) {
	var log ='INSERT INTO actions SET ?';
	var action = {
		type: type,
		action: action,
		date_created: created
	}
	connection.query(log,action);
}