<?php get_header();?>
<header>
    <h1>Revpanda Test Project</h1>
</header>
<main id="revpanda">
    <section class="input-form">
        <div>
            <label for="input_a">Input A:</label>
            <input type="text" id="input_a" name="input_a">
        </div>
        <div>
            <label for="input_b">Input B:</label>
            <input type="number" id="input_b" name="input_b">
        </div>
        <div>
            <label for="input_c">Input C:</label>
            <input type="text" id="input_c" name="input_c">
        </div>
        <input id="revpanda-submit" type="submit" value="Submit">
    </section>
    <section class="display-results">
        <div class="buttons">
            <button class="first">Display the "A" table values</button>
            <button class="second">Display "A", "B," "C" table values, in that order</button>
            <button class="third">Display "C" and "B" table values, in that order</button>
            <button class="fourth">Display "B" table values in ascending order</button>
            <button class="fifth">Display "B" table values in descending order</button>
        </div>
        <div id="results"></div>
    </section>
</main>
<footer>
    <p>Created by <a href="https://imoptimal.com" target="_blank">Imoptimal</a></p>
    <?php get_footer();?>
</footer>