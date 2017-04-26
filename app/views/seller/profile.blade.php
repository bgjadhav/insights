
      

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
   
   
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Publisher Profile</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center">  </div>
                
                <!--<div class="col-xs-10 col-sm-10 hidden-md hidden-lg"> <br>
                  <dl>
                    <dt>DEPARTMENT:</dt>
                    <dd>Administrator</dd>
                    <dt>HIRE DATE</dt>
                    <dd>11/12/2013</dd>
                    <dt>DATE OF BIRTH</dt>
                       <dd>11/12/2013</dd>
                    <dt>GENDER</dt>
                    <dd>Male</dd>
                  </dl>
                </div>-->
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                   @if(count(@data) > 0)
                      <tr>
                        <td> <b>Publisher Information: </b></td>
                        <td></td>
                      </tr>
                      
                      <tr>
                        <td>Name:</td>
                        <td>{{ $data['displayName'] }}</td>
                      </tr>
                   
                         <tr>
                             <tr>
                        <td>Logo:</td>
                       <td><img src="{{ $data['logoUrl'] }}" height="200" width="200"></td>
                      </tr>
                       
                      <tr>
                        <td>Contact:</td>
                        <td>{{ $data['directDealsContact'] }}</td>
                      
                     </tr>
                     <tr>
                        <td>Program Contact:</td>
                        <td>{{ $data['programmaticDealsContact'] }}</td>
                      
                     </tr>
                     
                     @else
                      <tr>
                        <td>No contact detail found for this publisher</td>
                       
                      
                     </tr>
                     @endif
                                            
                     
                    </tbody>
                  </table>
                  
                  
                </div>
              </div>
            </div>
                
            
          </div>
        </div>
      
    