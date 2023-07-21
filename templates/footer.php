<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>

     <script>
        // Met en place le background trailer au load de la page
        var initialTrailerUrl = "<?php echo isset($movies) && isset($movies[0]) ? getMovieTrailerURL($movies[0]['id']) : ''; ?>";
        if (initialTrailerUrl) {
            $('#trailerIframe').attr('src', initialTrailerUrl);
        }

        // Mettre à jour le trailer quand on change de slide
        $('#movieCarousel').on('slid.bs.carousel', function () {
            var carouselItemIndex = $('.carousel-item.active').index();
            var trailerUrl = "<?php echo isset($movies) && isset($movies[0]) ? getMovieTrailerURL($movies[0]['id']) : ''; ?>";
            if (trailerUrl) {
                $('#trailerIframe').attr('src', trailerUrl);
            }
        });
    </script>

</body>

</html>