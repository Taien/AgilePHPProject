<?php

    if (isset($_SESSION["UserId"]))
    {
        echo '<ul>
                  <li>
                      Upcoming Events
                      <div id="eventText">05/25/2017 5:00pm</div>
                      <hr/>
                      <div id="eventText">Create and view events here for any group you are a member of.</div>
                  </li>
              </ul>';
    }
    else
    {
        echo '<ul>
                  <li>
                      This is your event panel, where you can plan events for your team and make sure they never miss an appointment.
                  </li>
              </ul>';
    }
?>
