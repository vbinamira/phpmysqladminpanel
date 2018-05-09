// Dependencies ========================================
var express         = require('express');
var mysql        	= require('mysql');
var port            = process.env.PORT || 3000;
var morgan          = require('morgan');
var bodyParser      = require('body-parser');
var cookieParser	= require('cookie-parser');
var methodOverride  = require('method-override');
var passport		= require('passport');
var flash 			= require('connect-flash');
var md5 			= require('crypto-js/md5');
var session			= require('express-session');
var app             = express();

// Configuration ======================================

// Sets the connection to Mysql database
var connection = mysql.createConnection({
  host     : '10.107.1.23',
  user     : 'DSP-dev',
  password : 'dspcoreDevops@1',
  database : 'ticketadmin'
});
connection.connect();

// Logging and Parsing
app.use(express.static(__dirname + '/public'));                 // sets the static files location to public
app.use('/node_modules',  express.static(__dirname + '/node_modules')); // Use node_modules
app.use(morgan('dev'));                                         // log with Morgan
app.use(bodyParser.json());                                     // parse application/json
app.use(bodyParser.urlencoded({extended: true}));               // parse application/x-www-form-urlencoded
app.use(bodyParser.text());                                     // allows bodyParser to look at raw text
app.use(bodyParser.json({ type: 'application/vnd.api+json'}));  // parse application/vnd.api+json as json
app.use(cookieParser());										// read cookies (needed for auth)
app.use(methodOverride());
app.set('view engine', 'ejs');
// required for passport
app.use(session({ 
	secret: 'thereisnocowlevel',
	resave: false,
	saveUninitialized: false
	}));	// session secret
app.use(passport.initialize());	
app.use(passport.session());						// persistent login sessions
app.use(flash());									// use connect-flash for flash messages stored in session

// Routes ================================================
require('./app/user.js')(app, passport);

// Listen ================================================
app.listen(port);
console.log('App listening on port ' + port);