<h1>Heading 1</h1>
<h2>Heading 2</h2>
<h3>Heading 3</h3>
<h4>Heading 4</h4>
<h5>Heading 5</h5>
<h6>Heading 6</h6>

<hr />

<hgroup title="The hgroup element represents the heading of a section. The element is used to group a set of h1–h6 elements when the heading has multiple levels, such as subheadings, alternative titles, or taglines.">
    <h1>Heading Group: H1</h1>
    <h2>Heading Group: H2</h2>
</hgroup>

<p>
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
<a href="http://www.w3schools.com">This is a link </a>
At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
</p>
<p>
    Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
    <img src={"planets.gif"|ezimage} width="200" height="150" />
    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
</p>

<hr />

<h2>Figure, image and figcaption</h2>
<figure title="The figure element represents some flow content, optionally with a caption, that is self-contained and is typically referenced as a single unit from the main flow of the document.">
 <img src={'planets.gif'|ezimage()} alt="Lorem ipsum dolor sit amet, consetetur sadipscing elitr.">
 <figcaption>Figcaption - Lorem ipsum dolor sit amet</figcaption>
</figure>

<hr />

<h2>Text-Formatting</h2>

<p>
This is normal text.
<b>This text is bold</b>.
<i>This text is italic</i>.
<em>Emphasized text</em>.
<strong>Strong text</strong>.
<small>Small text</small>.
<dfn>Definition term</dfn>.
<code>A piece of computer code</code>
<samp>Sample output from a computer program</samp>.
<kbd>Keyboard input</kbd>.
<var>Variable</var>.
<cite>Citation</cite>.
I have a date on <time datetime="2008-02-14">Valentines day</time>.
<mark>This text is marked</mark>
The <abbr title="World Health Organization">WHO</abbr> was founded in 1948. 
</p>
<p>This is<sub> subscript</sub> and <sup>superscript</sup></p>

<hr />

<h2>Table with caption</h2>

<table summary="The Summary of the table: 2 rows and 2 cells">
    <caption>Table Caption</caption>
    <tr>
        <th>Header 1</th>
        <th>Header 2</th>
    </tr>
    <tr>
        <td>row 1, cell 1</td>
        <td>row 1, cell 2</td>
    </tr>
    <tr>
        <td>row 2, cell 1</td>
        <td>row 2, cell 2</td>
    </tr>
    <tr>
        <td>row 3, cell 1</td>
        <td>row 3, cell 2</td>
    </tr>
    <tr>
        <td>row 4, cell 1</td>
        <td>row 4, cell 2</td>
    </tr>
    <tr>
        <td>row 5, cell 1</td>
        <td>row 5, cell 2</td>
    </tr>
</table>


<hr />

<h2>Blockquote</h2>

<blockquote>
    Here is a long quotation here is a long quotation here is a long quotation here is a long quotation here is a long quotation here is a long quotation here is a long quotation here is a long quotation here is a long quotation.
</blockquote>

<hr />

<h2>Preformatted text</h2>

<pre>
    Text in a pre element
    is displayed in a fixed-width
    font, and it preserves
    both      spaces and
    line breaks
</pre>

<hr />

<h2>Video & Audio</h2>

<video width="320" height="240" controls="controls">
  <source src="movie.mp4" type="video/mp4" />
  <source src="movie.ogg" type="video/ogg" />
  Your browser does not support the video tag.
</video>
<audio controls="controls">
  <source src="song.ogg" type="audio/ogg" />
  <source src="song.mp3" type="audio/mp3" />
  Your browser does not support the audio tag.
</audio>

<hr />

<h2>Canvas / Iframe</h2>

<canvas id="myCanvas"></canvas>
<script type="text/javascript">
var canvas=document.getElementById('myCanvas');
var ctx=canvas.getContext('2d');
ctx.fillStyle='#FF0000';
ctx.fillRect(0,0,80,100);
</script>

<iframe src="http://www.w3schools.com"></iframe>


<hr />

<h2>Nested ordered and unordered lists</h2>

<ul>
  <li>Coffee</li>
  <li>Tea
    <ul>
    <li>Black tea</li>
    <li>Green tea</li>
    </ul>
  </li>
  <li>Milk</li>
</ul>

<ol>
  <li>Coffee</li>
  <li>Tea
    <ol>
    <li>Black tea</li>
    <li>Green tea</li>
    </ol>
  </li>
  <li>Milk</li>
</ol>

<hr />

<h2>Form elements</h2>

<form name="input" action={"/"|ezurl} method="get">
    <p>
        <legend>Sex:</legend>
        <label for="male"><input type="radio" name="sex" id="male" />Male</label>
        <label for="female"><input type="radio" name="sex" id="female" />Female</label>
        
        <label for="option">Yes / No<input type="checkbox" name="option" id="option" /></label>
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password" />
        
        <label for="user">User</label>
        <input type="text" name="user" id="user" />
        
        <label for="cars">Cars</label>
        <select name="cars" id="cars">
          <option value="volvo">Volvo</option>
          <option value="saab">Saab</option>
          <option value="mercedes">Mercedes</option>
          <option value="audi">Audi</option>
        </select>
        <label for="morecars">Cars</label>
        <select name="morecars" id="morecars">
          <optgroup label="Swedish Cars">
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
          </optgroup>
          <optgroup label="German Cars">
            <option value="mercedes">Mercedes</option>
            <option value="audi">Audi</option>
          </optgroup>
        </select>
        <label for="message">Message</label>
        <textarea name="Message" id="message" rows="5" cols="40">
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
        </textarea> 
        <input type="submit" value="Submit" /> <button>Other button</button>
    </p>
    <fieldset>
        <legend>Personalia:</legend>
        <label for="firstname">Firstname</label>
        <input type="text" id="firstname"/>
        <label for="lastname">Lastname</label>
        <input type="text" id="lastname"/>
        <label for="city">City</label>
        <input type="text" id="city" />
    </fieldset>
</form>

<br />
<button>Button</button>

<hr />

<h2>Definition list</h2>

<dl>
    <dt>Coffee</dt>
    <dd>- black hot drink</dd>
    <dt>Milk</dt>
    <dd>- white cold drink</dd>
</dl>

<hr />

<h2>Address</h2>

<address>
    eZ Admin<br />
    <a href="mailto:nospam@example.com">Email Us</a><br />
    Address: 12345 Example City<br />
    Phone: +12 34 56 78
</address>

<hr />

<h2>Imagemap</h2>

<img src={"planets.gif"|ezimage} width="145" height="126" alt="Planets" title="Planets" usemap="#planetmap" />

<map name="planetmap">
  <area shape="rect" coords="0,0,82,126" href="sun.htm" alt="Sun" />
  <area shape="circle" coords="90,58,3" href="mercur.htm" alt="Mercury" />
  <area shape="circle" coords="124,58,8" href="venus.htm" alt="Venus" />
</map>

<details>
    <summary>Summary</summary>
    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
</details>
