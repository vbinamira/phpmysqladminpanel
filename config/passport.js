// config/passport.js
// load dependencies
var LocalStrategy   = require('passport-local').Strategy;
var mysql 			= require('mysql');
var dbcon = mysql.createConnection({
  host     : '10.107.1.23',
  user     : 'DSP-dev',
  password : 'dspcoreDevops@1',
  database : 'ticketadmin'
});
// expose this function to our app using module.exports
module.exports = function(passport) {

    // =========================================================================
    // passport session setup ==================================================
    // =========================================================================
    // required for persistent login sessions
    // passport needs ability to serialize and unserialize users out of session

    // used to serialize the user for the session
    passport.serializeUser(function(user, done) {
        done(null, user.id);
    });

    // used to deserialize the user
    passport.deserializeUser(function(id, done) {
        dbcon.query("SELECT * FROM users WHERE id = " + id, function(err,rows){
        	done(err,rows[0]);
        });
    });
    // =========================================================================
    // LOCAL SIGNUP ============================================================
    // =========================================================================
    // we are using named strategies since we have one for login and one for signup
    // by default, if there was no name, it would just be called 'local'
    passport.use('local-signup', new LocalStrategy({
        // by default, local strategy uses username and password, we will override with email
        usernameField : 'email',
        passwordField : 'password',
        passReqToCallback : true // allows us to pass back the entire request to the callback
    },
    function(req, email, password, done) {
        // asynchronous
        process.nextTick(function() {
        	connection.query('SELECT * from users WHERE email = ?',[email], function(err,rows,fields){
        	if (err) {
                return done(err);
            }
                if (!rows.length) {
                    return done(null, false, req.flash('loginMessage', 'No user found.')); // req.flash is the way to set flashdata using connect-flash
                } 
                // if the user is found but the password is wrong
                if (!( rows[0].password == password)) {
                    return done(null, false, req.flash('loginMessage', 'Oops! Wrong password.')); // create the loginMessage and save it to session as flashdata
                }
                // all is well, return successful user
                return done(null, rows[0]);         
        	});
        });
    }));
};