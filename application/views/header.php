<div class="ui orange fixed inverted menu">
  <div class="ui container">
    <a href="<?php echo base_url(); ?>landingpage" class="header item">
      <img class="logo" src="<?php echo base_url('assets/images/logo.png'); ?>">&nbsp;&nbsp;Barriér
    </a>
    <div class="right menu">
      <div id="warning">
      <a class="item" id="misplaced_barriers_menu">
        <center><i class="big warning sign icon"></i></center>
      </a>
      </div>
        <div id="misplaced_barriers_modal" class="ui small modal">
          <div class="header">
            <i class="red warning sign icon"></i> Warning! Barriers have moved!
          </div>
          <div class="content">
            <table class="ui four column basic table">
              <thead>
                <tr>
                  <th rowspan="2">Barrier ID</th>
                  <th colspan="2">Coordinates</th>
                  <!-- <th rowspan="2">Location</th> -->
                </tr>
                <tr>
                  <th>Latitude</th>
                  <th>Longitude</th>
                </tr>
              </thead>
              <tbody id="moved_markers">
              </tbody>
            </table>
          <div class="ui secondary inverted red segment"><center>The markers for these have turned red. Please attend to these barriers as soon as possible.</center></div>
          </div>
        </div>        
      


      <a class="item" id="add_menu">
        <i class="big add circle icon"></i>
        Add Barrier
        <div id="add_barrier_modal" class="ui long modal">
          <div class="header">
            <i class="add circle icon"></i>
            Add Barrier
          </div>
          <div class="content">
            <form id="add_form" class="ui form">
              <div class="two required fields">
                <div class="field">
                  <label>Barrier ID</label>
                  <input type="text" id="barrier_id" maxlength="50" placeholder="Barrier XX">
                </div>
                <div class="field">
                  <label>Barrier Key</label>
                  <input type="text" id="barrier_key" maxlength="50" placeholder="a0s5d86a2s">
                </div>
              </div>
              <h4 class="ui dividing header">Coordinates</h4>
              <div class="two required fields">
                <div class="field">
                  <label>Latitude</label>
                  <input type="text" id="latitude" maxlength="30" placeholder="00.0000">
                </div>
                <div class="field">
                  <label>Longitude</label>
                  <input type="text" id="longitude" maxlength="30" placeholder="00.0000">
                </div>
            </div>
            </form>
            <div class="ui equal width center aligned grid">
              <div class="row">
                <div class="column">
                  <button class="fluid ui orange button top attached" id="curr">Same as current location</button>
                  <div class="ui attached message">
                  Click the <i class=" crosshairs icon"></i> icon located at the lower right hand side of the screen to get the current location.
                  </div>
                </div>
                <div class="column">
                  <button class="fluid ui orange button top attached" id="added">Same as marker added on map</button>
                  <div class="ui attached message">
                  Click the place on the map where you want to add a new barrier. Note that you can only add one at a time.
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="actions">
            <div class="ui blue basic reset button">Reset
            </div>
            <div class="ui red cancel button">Cancel
            </div>
            <button class="ui green submit button" type="submit" id="save">Save</button>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>
