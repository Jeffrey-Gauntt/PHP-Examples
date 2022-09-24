<?php
ob_start();

include ("../includes/config.php");
include ("../includes/userPhpSessions.php");
include ("../includes/classes/PlaylistResultsListElementProvider.php");

$userId = $_POST['userId'];
$playlistName = $_POST['playlistName'];

//get playlist Id
$query = mysqli_query($con, "SELECT playlist_id FROM song_playlists WHERE name = '$playlistName' AND id = '$userId'");
$array = mysqli_fetch_array($query);
$uniqueArray = array_unique($array);
$playlistId = implode($uniqueArray);

//get track id's from playlist onto an array in proper order
$query = mysqli_query($con, "SELECT * FROM song_playlist_tracks WHERE song_playlist_id = '$playlistId'");
if($query->num_rows > 0) {
    $playlistTrackIds = array();
    while($row = mysqli_fetch_array($query)) {
    array_push($playlistTrackIds, $row['track_id']);
    }
    $playlist = implode("', '", $playlistTrackIds);
    //query tracks to results list
    $query = mysqli_query($con, "SELECT * FROM songs WHERE status = '1' AND perm = '1' AND song_id IN ('" . $playlist . "') ORDER BY RAND()");
    if($query->num_rows > 0) {
        $currentPlaylist = array();
        while($row = mysqli_fetch_array($query)) {
            array_push($currentPlaylist, $row['track_id']);
            $currentPlaylistJson = json_encode($currentPlaylist);

			// get shareable profile link
			$userShareId = $row['user_id'];
			$shareableProfileLink = mysqli_query($con, "SELECT * FROM members WHERE id = '$userShareId'");
			$shareableProfileLink = mysqli_fetch_assoc($shareableProfileLink);
			$shareableProfileLink = $shareableProfileLink['link_share'];

            ?>
            <section title="Click to play" class="resultitem" onclick="setTrack('<?= $row['id']; ?>', newPlaylist, true)">
                <article class="resultimgcontainer">
                    <img src="<?= $row['picture_path']; ?>" alt="User Picture" class="resultimg">
                </article>
                <article class="resultinfo">
                    <div class="resultinfotop">
                        <div class="resultinfoleft">
                            <p class="resulttrackname"><?= $row['title']; ?></p>
                            <p class="resultusername"><?= $row['name']; ?></p>
                            <!-- <p class="resultuserregion"><?= $row['country']; ?></p> -->
                            <p class="resultuserregion regionSelect" name="regionSelect" value="<?= $row['area']; ?>"><?= $row['area']; ?></p>
                        </div>
                        <div class="resultinforight">
                            <p class="resultplays">Plays: <span class="resultplaycount"><?= $row['plays']; ?></span></p>
                            <div id="likesdislikesmorecontainer">
                                <?php
                                $listElements = new PlaylistResultsListElementProvider($con, $userIdLoggedIn, $row['id'], $row['likes'], $row['dislikes'], $playlistId, $row['id'], $row['name'], $shareableProfileLink);
                                echo $listElements->createPlaylistResultsListElements();        
                                ?>  
                            </div>
                        </div>
                    </div>
                    <p class="resultgenres"><?php echo $row['primary'];
        
                                                                    if($row['secondary'] != "none") {
                                                                        echo ", " . $row['secondary'];
                                                                    }

                                                                                    if($row['tertiary'] != "none") {
                                                                                        echo ", " . $row['tertiary'];
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
    var newList = <?php echo $currentPlaylistJson; ?>;
    // console.log("My Mix, " + newList);
</script>



<!-- TO DO: pagination of results -->
