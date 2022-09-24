<?php
ob_start();

include ("../includes/config.php");
include ("../includes/userPhpSessions.php");
include ("../includes/classes/PlaylistResultsListElementProvider.php");

$userId = $_POST['userId'];
$playlistName = $_POST['playlistName'];

//get playlist Id
$query = mysqli_query($con, "SELECT playlist_id FROM playlists WHERE name = '$playlistName' AND user_id = '$userId'");
$array = mysqli_fetch_array($query);
$uniqueArray = array_unique($array);
$playlistId = implode($uniqueArray);

//get track id's from playlist onto an array in proper order
$query = mysqli_query($con, "SELECT * FROM playlist_tracks WHERE playlist_id = '$playlistId'");
if($query->num_rows > 0) {
    $playlistTrackIds = array();
    while($row = mysqli_fetch_array($query)) {
    array_push($playlistTrackIds, $row['track_id']);
    }
    $playlist = implode("', '", $playlistTrackIds);
    //query tracks to results list
    $query = mysqli_query($con, "SELECT * FROM user_tracks WHERE statuss = 'Active' AND perm = 'yes' AND track_id IN ('" . $playlist . "') ORDER BY RAND()");
    if($query->num_rows > 0) {
        $currentPlaylist = array();
        while($row = mysqli_fetch_array($query)) {
            array_push($currentPlaylist, $row['track_id']);
            $currentPlaylistJson = json_encode($currentPlaylist);

			// get shareable profile link
			$userShareId = $row['user_id'];
			$shareableProfileLink = mysqli_query($con, "SELECT * FROM users WHERE user_id = '$userShareId'");
			$shareableProfileLink = mysqli_fetch_assoc($shareableProfileLink);
			$shareableProfileLink = $shareableProfileLink['shareable_link'];

            ?>
            <section title="Click to play" class="resultitem" onclick="setTrack('<?= $row['track_id']; ?>', newPlaylist, true)">
                <article class="resultimgcontainer">
                    <img src="<?= $row['picture_path']; ?>" alt="User Picture" class="resultimg">
                </article>
                <article class="resultinfo">
                    <div class="resultinfotop">
                        <div class="resultinfoleft">
                            <p class="resulttrackname"><?= $row['track_title']; ?></p>
                            <p class="resultusername"><?= $row['username']; ?></p>
                            <!-- <p class="resultuserregion"><?= $row['country']; ?></p> -->
                            <p class="resultuserregion regionSelect" name="regionSelect" value="<?= $row['region']; ?>"><?= $row['region']; ?></p>
                        </div>
                        <div class="resultinforight">
                            <p class="resultplays">Plays: <span class="resultplaycount"><?= $row['plays']; ?></span></p>
                            <div id="likesdislikesmorecontainer">
                                <?php
                                $listElements = new PlaylistResultsListElementProvider($con, $userIdLoggedIn, $row['track_id'], $row['likes'], $row['dislikes'], $playlistId, $row['user_id'], $row['username'], $shareableProfileLink);
                                echo $listElements->createPlaylistResultsListElements();        
                                ?>  
                            </div>
                        </div>
                    </div>
                    <p class="resultgenres"><?php echo $row['primary_genre'];
        
                                                                    if($row['secondary_genre'] != "none") {
                                                                        echo ", " . $row['secondary_genre'];
                                                                    }

                                                                                    if($row['tertiary_genre'] != "none") {
                                                                                        echo ", " . $row['tertiary_genre'];
                                                                                    }
                                            ?>
                    </p>
                </article>
            </section>

        <?php  }

    } else {
        ?>
        <h2 id='resultsHeading'>No Tracks available in My Mix</h2>
        <?php
        $currentPlaylistJson = 0;
    }   

} else {
    ?>
    <h2 id='resultsHeading'>No Tracks added to My Mix yet</h2>
    <?php
    $currentPlaylistJson = 0;
}
?>

<script>
    var newPlaylist = <?php echo $currentPlaylistJson; ?>;
    // console.log("My Mix, " + newPlaylist);
</script>



<!-- TO DO: pagination of results -->