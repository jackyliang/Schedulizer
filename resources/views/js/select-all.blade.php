<script type="text/javascript">
    $(function()
    {
        $('input').on('focus', function (e) {
            $(this)
                .one('mouseup', function () {
                    $(this).select();
                    return false;
                })
                .select();
        });
    });
</script>
