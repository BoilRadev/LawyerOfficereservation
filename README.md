<h1>DelaSport Task Project</h1>
<tr>
<h3>User Journey</h3>

<p>This is a Phalcon PHP Project that simulates site of a lawyers office, where clients can book appointments with the lawyers working in it ,for their law needs. Once we enter the site we see the "Home" page which lists all existing lawyers that operate on this site. Every lawyer is displayed with all of their info such as name, email address , address , fee and right after each lawyer there is a button "Make an appointment", which we can click if the lawyer suit us. We can arange an appointment only if we are registered user. We can achieve this by simply click the "Register" button on the navigation bar in the top left corner of the page. To do a successfull registration we have to pass several validations:</p>

    BackEnd:
    ->PresenceOfValidation
    ->UniquenessValidation
    ->EmailValidation
    ->ConfirmPassword

    FronEnd:
    ->emailField
    ->strong password check
    ->disable "Create" button

<p>After successfully passing all of the above we are redirected to the "Home" page. Now we have to login and easly explore
    the site in two different ways.</p>

<h3>As Client</h3>

    If we register as client, we can make an apointment with lawyer of our choice. After clicking the "Make an appointment" button,
    we are redirected to the create page where we can freely choose a date and time for our appointment with the lawyer. If the hour
    of our appointment is already taken by somebody before us, we will be notified that we must select a different scope for the
    meeting. Once submited successfully it is passed to the chosenlawyer as 'pending'.

<h3>As Lawyer</h3>

    If we register as lawyer, we are straight redirect to the page of our personal appointments.There we can manipulate the already
    made appoinments by the clients. We can Approve/Decline the existing appointments or even edit them if needed to. Also we check 
    our schedule by clicking on calendar where all the approved meetings pop up.


----------------------------------------------------------------------------------------------------------------------------------------

<h3>Used Technologies</h3>

<h4>BCrypt</h4>

<p>For better security we use password encryption, which allow us to hash the entered password and make it more reliable.</p>

    $user->setpassword($this->security->hash($password));

<h4>Pagination</h4>

<p>In case we have to much information in our DataBase and it would not be adecuate to list it all at once, we use paagination.
With pagination we control how the information appear in lists</p>

    $paginator = new Paginator(
             [
                    'data'  => Source::find(),
                    'limit' => 5,
                    'page'  => $this->request->getQuery('page', 'int', 1),
                ]
            );

            $this->view->page = $paginator->getPaginate();

<h4>displayCalendar</h4>

<p>This is a 3rd party tool that allow us to visualize all our appointments in a calendar version scheduled by hour/day/week/month.</p>

    https://fullcalendar.io/
