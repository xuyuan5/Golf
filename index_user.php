<header class='container'>
<div class='row'>
    <div class='six columns bar-item'>
        <h3>Golf Tracker</h3>
    </div>
    <div id='user-manager' class='six columns bar-item'>
        <div class='row'>
            <div class='five columns offset-by-four'>
<?php
require_once('database/users.php');
?>
                <a id='edit-user' class="radius small white button form-button">
<?php
global $loggedInUser;
echo $loggedInUser->email;
?>
                </a>
            </div>
            <div class='three columns'>
                <a id='logout-user' class="radius small white button form-button">Logout</a>
            </div>
        </div>
    </div>
</div>
</header>
<div class='container'>
    <div class='row'>
        <div id='main' class='six columns'>
            <div class='row'>
    			<a class="nice radius small white button" id='add-score-button'>Add Score</a>
			</div>
        </div>
        <div id='courses' class="four columns offset-by-one panel">
            <div class='row header'>
                <h5>Courses</h5>
            </div>
            <div class='row footer'>
                <a class="nice radius small white button add-course">Add</a>
            </div>
        </div>
    </div>
    
    <div id='tee_form' class='five columns' title='Tee Details'>
        <form class='nice custom'>
            <input id='tee-name' type='text' placeholder='Tee Name' class='medium input-text'/>
            <div class='row'>
                <div class='two columns'>
                    <input id='slope' type='text' placeholder='Slope' class='small input-text'/>
                </div>
                <div class='two columns'>
                    <input id='rating' type='text' placeholder='Rating' class='small input-text'/>
                </div>
            </div>
            <div class='row'>
                <label for="ladies-tee" id='ladies-tee-label'>
                    <input type="checkbox" id="ladies-tee" style="display: none;">
                    <span class="custom checkbox"></span> Is Ladies' Tee
                </label>
            </div>
            <div class='row'>
                <div class='two columns'>
                    <label>Par</label>
                </div>
                <div class='two columns'>
                    <label>Handicap</label>
                </div>
            </div>
            <div class='row'>
                <div class='one columns' id='par-f9'>
                    <input type='number' placeholder='1' class='small input-text number'/>
                    <input type='number' placeholder='2' class='small input-text number'/>
                    <input type='number' placeholder='3' class='small input-text number'/>
                    <input type='number' placeholder='4' class='small input-text number'/>
                    <input type='number' placeholder='5' class='small input-text number'/>
                    <input type='number' placeholder='6' class='small input-text number'/>
                    <input type='number' placeholder='7' class='small input-text number'/>
                    <input type='number' placeholder='8' class='small input-text number'/>
                    <input type='number' placeholder='9' class='small input-text number'/>
                </div>
                <div class='one columns back-9' id='par-b9'>
                    <input type='number' placeholder='10' class='small input-text number back-9'/>
                    <input type='number' placeholder='11' class='small input-text number back-9'/>
                    <input type='number' placeholder='12' class='small input-text number back-9'/>
                    <input type='number' placeholder='13' class='small input-text number back-9'/>
                    <input type='number' placeholder='14' class='small input-text number back-9'/>
                    <input type='number' placeholder='15' class='small input-text number back-9'/>
                    <input type='number' placeholder='16' class='small input-text number back-9'/>
                    <input type='number' placeholder='17' class='small input-text number back-9'/>
                    <input type='number' placeholder='18' class='small input-text number back-9'/>
                </div>
                <div class='one columns' id='handicap-f9'>
                    <input type='number' placeholder='1' class='small input-text number'/>
                    <input type='number' placeholder='2' class='small input-text number'/>
                    <input type='number' placeholder='3' class='small input-text number'/>
                    <input type='number' placeholder='4' class='small input-text number'/>
                    <input type='number' placeholder='5' class='small input-text number'/>
                    <input type='number' placeholder='6' class='small input-text number'/>
                    <input type='number' placeholder='7' class='small input-text number'/>
                    <input type='number' placeholder='8' class='small input-text number'/>
                    <input type='number' placeholder='9' class='small input-text number'/>
                </div>
                <div class='one columns back-9' id='handicap-b9'>
                    <input type='number' placeholder='10' class='small input-text number back-9'/>
                    <input type='number' placeholder='11' class='small input-text number back-9'/>
                    <input type='number' placeholder='12' class='small input-text number back-9'/>
                    <input type='number' placeholder='13' class='small input-text number back-9'/>
                    <input type='number' placeholder='14' class='small input-text number back-9'/>
                    <input type='number' placeholder='15' class='small input-text number back-9'/>
                    <input type='number' placeholder='16' class='small input-text number back-9'/>
                    <input type='number' placeholder='17' class='small input-text number back-9'/>
                    <input type='number' placeholder='18' class='small input-text number back-9'/>
                </div>
            </div>
        </form>
        <div class='row h-buttons'>
            <div class='one columns'>
                <a class="nice radius small white button form-button update-tee">OK</a>
            </div>
            <div class='one columns offset-by-one'>
                <a class="nice radius small white button form-button cancel-tee-form">Cancel</a>
            </div>
        </div>
    </div>

    <div id='course_form' class='four columns' title='Course Details'>
        <form class='nice custom'>
            <input id='course-name' type='text' placeholder='Course Name' class='medium input-text'/>
            <div class='row'>
                <label for="9-holes" id='9-holes-label'>
                    <input name="holes" type="radio" id="9-holes" style="display:none;">
                    <span class="custom radio"></span> 9-holes
                </label>
                <label for="18-holes" id='18-holes-label'>
                    <input name="holes" type="radio" id="18-holes" style="display:none;">
                    <span class="custom checked radio"></span> 18-holes
                </label>
            </div>
            <div class='row'>
                <label>Available Tee</label>
            </div>
            <div class='row' id='add-tee-row'>
                <div class='four columns'>
                    <a class="nice radius small white button add-tee">Add New Tee Data</a>
                </div>
            </div>
        </form>
        <div class='row h-buttons'>
            <div class='one columns'>
                <a class="nice radius small white button form-button update-course">OK</a>
            </div>
            <div class='one columns offset-by-three'>
                <a class="nice radius small white button form-button cancel-form">Cancel</a>
            </div>
        </div>
    </div>
</div>

<div id='user-details'>
    <div id='handicap-overall'>Loading ...</div>
</div>

<form id='add-score-form' class='nice custom'>
    <div id='add-score-meta'>
        <input type='date' placeholder='Date' class='small text-input date'/>
        <div class="custom dropdown course-dropdown">
            <a href="#" class="current">Select A Course</a>
            <a href="#" class="selector"></a>
            <ul id='add-score-courses-list'></ul>
        </div>
        <div class="custom dropdown tee-dropdown">
            <a href="#" class="current">Select A Tee</a>
            <a href="#" class="selector"></a>
            <ul id='add-score-tees-list'></ul>
        </div>
        <a id='add-score-next-button' class="nice radius small white button">Next</a>
    </div>
    <div id='add-score-next'>
        <table class='score'>
            <tr class='front-9'>
                <td>#1 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#2 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#3 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#4 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#5 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#6 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#7 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#8 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#9 <input type='number' min='1' max='10' class='small input-text'/></td>
            </tr>
            <tr class='back-9'>
                <td>#10 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#11 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#12 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#13 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#14 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#15 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#16 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#17 <input type='number' min='1' max='10' class='small input-text'/></td>
                <td>#18 <input type='number' min='1' max='10' class='small input-text'/></td>
            </tr>
        </table>
        <a id='submit-score-button' class="nice radius small white button">OK</a>
    </div>
</form>
    
<div id='user-form'>
    <input type='email' placeholder='Email' class='medium input-text'/>
    <a id='form-user' class="nice radius small white button">Submit</a>
</div>