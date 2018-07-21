Problem set

To use ajax to prevent page reload after fom submission. there are two forms on the website, the booking form on the header and the contact form around  the footer.

second problem is to implement appwebsms' sms api that'd send form values to a specifed phone number after every form  submission. here's the link to their documentation https://appwebsms.com/api

the php file named 'before.php' was the former perfectly working form handler just that it didnt implement ajax thus it  had to reload on submission.

I plan to use a modal to display the success message after submission but if you got a better implementation, still cool!

the current state works to an extent.
cheers bro!

<div class="container" id="loaderModal">
                  <div class="container text-center loader-content">
                    <a href="#"><span class="hide">X</span></a>
                    <h1 class="text-center">Appointment Booked</h1>

                    <div class="circle-loader text-center">
                      <div class="checkmark draw text-center"></div>
                    </div>

                    <p><button id="toggle" type="button" class="btn btn-success">Close</button></p>
                  </div>
                </div>

<section class="probootstrap_section" id="section-Howitworks">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-12 text-center mb-5 probootstrap-animate">
            <h2 class="display-4 border-bottom probootstrap-section-heading">How it works</h2>
            <blockquote class="">
              <p class="lead mb-4"><em>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</em></p>
            </blockquote>

          </div>
        </div>
        
      </div>
    </section> 