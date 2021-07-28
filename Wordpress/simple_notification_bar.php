<?php
  
  #
  # Required ACF plugin to populate data and indicate roatation status
  #

  add_action('wp_body_open', '___wp_body_open', 20);
  function ___wp_body_open(){

    # Change this to control the time it takes the texts to switch up (in ms)
    $notation_time = 5000;

    # Indicate whether the texts should be rotatable
    $is_rotating = get_field('is_rotating', 'options') ?? false;

    # Change this to populate the array with different rotating texts
    $notifications = get_field('notifications', 'options');
    $_notifications = [];
    foreach($notifications as $notify){
      $_notifications[] = $notify['text'];
    }

    $first_notify = $_notifications[0];
    $_notifications = json_encode($_notifications, JSON_UNESCAPED_UNICODE);

    ?>
      <div class="notification-bar">
        <p data-current=0 >
          <?php echo $first_notify; ?>
        </p>
      </div>
      <style>
        .notification-bar {
          background: #252525;
          color: white;
          padding: 12px;
          text-align: center;
          font-weight: bold;
          font-size: 12px;
          border-bottom: 1px solid white;
        }
        .notification-bar p {
          margin: 0;
        }
      </style>

      <?php
        # print rotation script if allowed
        if($is_rotating){
          ?>
            <script>
              var notifications = <?php echo $_notifications; ?>;

              setInterval(() => {
                let notify = document.querySelector('.notification-bar p');
                let currentOne = parseInt(notify.dataset.current);
                let nextOne = (currentOne + 1) > 2 ? 0 : currentOne = currentOne + 1;
                notify.dataset.current = nextOne;
                notify.innerText = notifications[nextOne];
              }, <?php echo $notation_time; ?>);
            </script>
          <?php
        }
  }
