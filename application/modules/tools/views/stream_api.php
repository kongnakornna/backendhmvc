<form>
  <label for="uuid">UUID</label><input type="text" name="uuid" value="<?=$this->input->get('uuid') ? $this->input->get('uuid') :'be02b035-7c18-4fe9-89bf-60d1961b72dc'?>" />
  <br/>
  <label for="agent">AGENT</label>
    <select name="agent">
    <option value="IOS" <?='IOS'==$this->input->get('agent') ? 'selected' : '' ?>>IOS</option>
    <option value="ANDROID" <?='ANDROID'==$this->input->get('agent') ? 'selected' : '' ?>>ANDROID</option>
  </select>
  <br/>
  <label for="ch">CHANNEL</label>
  <select name="ch">
    <option value="139" <?='139'==$this->input->get('ch') ? 'selected' : '' ?>>ทรูปลูกปัญญา HD</option>
    <option value="107" <?='107'==$this->input->get('ch') ? 'selected' : '' ?>>สามเณรปูกปัญญา HD</option>
  </select>
    <br/>
  <button type="submit">SUBMIT</button>
  <a href="<?=$url?>" target="_blank">LINK</a>
  <BR>
  send URL : <?=$url?>
</form>