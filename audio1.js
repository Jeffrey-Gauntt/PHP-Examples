//TO DO: how to make this greeting thing work right
var greeting = [183];



var currentPlaylist = [];
var shufflePlaylist = [];
var audioElement;
var newPlaylist = greeting;//TO DO: the mix opening track id... record opening track
var mouseDown = false;
var currentIndex = 0;
var shuffle = false;

//format time
  function formatTime(seconds) {
    var time = Math.round(seconds);
    var minutes = Math.floor(time / 60);
    var seconds = time - (minutes * 60);

    var extraZero;
    //extra zero for correct display of time
    if(seconds < 10) {
      extraZero = "0";
    } else {
      extraZero = "";
    }

    return minutes + ":" + extraZero + seconds;
  }

//current play time position progress bar update
  function updateProgress(audio) {
    //current play position text
    $("#currentPlayTimePosition").text(formatTime(audio.currentTime));
    $("#currentPlayTimeRemaining").text(formatTime(audio.duration - audio.currentTime));

        // phone
    $("#phoneCurrentPlayTimePosition").text(formatTime(audio.currentTime));
    $("#phoneCurrentPlayTimeRemaining").text(formatTime(audio.duration - audio.currentTime));

    //progress bar
    var progress = audio.currentTime / audio.duration * 100;
    $("#progressBar").css("width", progress + "%");
        // phone
    $("#phoneProgressBar").css("width", progress + "%");
  }

//audio class
  function Audio() {
    //property of class,
    this.currentlyPlaying;
    this.currentlyPlayingUser;
    this.audio = document.createElement('audio');
    this.adAudio = document.createElement('audio');
  
    // end track
      this.audio.addEventListener("ended", function() {
          if(currentPlaylist == greeting){
              pauseTrack();
          } else {
              pauseTrack();
              nextTrack();
          }
      });

    //updating track time displays
    this.audio.addEventListener("canplay", function() {
      //"this" refers to the object that the event was called on
      var duration = formatTime(this.duration);
      $("#currentPlayTimeRemaining").text(duration);
    });

    this.adAudio.addEventListener("canplay", function() {
      //"this" refers to the object that the event was called on
      var durationAd = formatTime(this.duration);
      $("#currentPlayTimeRemaining").text(durationAd);
    });

    //time update
    this.audio.addEventListener("timeupdate", function() {
      if(this.duration) {
        updateProgress(this);
      }
    });

    //update progress event listener
    this.adAudio.addEventListener("timeupdate", function() {
      if(this.duration) {
        updateProgress(this);
      }
    });

    //choosing the ad to play
    this.setAd = function(source) {
      this.adAudio.src = source;
      // audioElement.audioEnding(source);
    }

    //choosing the track to play
    this.setTrack = function(track) {
      this.currentlyPlayingUser = track.username;
      this.currentlyPlaying = track.track_id;
      this.audio.src = track.track_file_path;
    }

    //play
    this.play = function() {
      this.audio.play();
    }
    
    //pause
    this.pause = function() {
      this.audio.pause();
    }
    
    //dragging progress bar 3 of 3
    this.setTime = function(seconds) {
      this.audio.currentTime = seconds;
    }
  }
