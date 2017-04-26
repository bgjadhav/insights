<div class="pages" id="switch_options">

		<label id="lroadmap" class="switch_name <?php if ($subproject  == 'roadmap') echo 'activate'?>">
			<input class="switch_project" type="radio" name="candidateP" id="ProdRoadmap" style="margin-right:4px;" value="roadmap" <?php if ($subproject  == 'roadmap') echo 'checked' ?>>
			Roadmap
		</label>

		<label id="lcandidate" class="switch_name  <?php if ($subproject  == 'candidate') echo 'activate'?>">
			<input class="switch_project"  type="radio" name="candidateP" id="ProdCandidate" value="candidate" style="margin-left:10px;margin-right:4px;" <?php if ($subproject  == 'candidate') echo 'checked'?>>
			Candidates
		</label>
</div>
