<?php

$alpha = 'CC';
$color_read = "#FF0000";
$color_write = "#00FF00";
$color_line = '#000000';

# stats are in pairs for each device, reads first then writes
for($i=0; $i<count($this->DS); $i+=4) {
    list($type, $dev) = explode('_', $this->DS[$i]['NAME']);
    echo "device = $dev\n";

    $graph = floor($i/2) + 1;

    $opt[$graph]     = "--vertical-label \"IOPS\" --title \"$hostname / IOPS $dev\" --lower=0";
    $def[$graph]     = '';
    $ds_name[$graph] = "IOPS $dev";

    $def[$graph]  = rrd::def   ('var1', $this->DS[$i]['RRDFILE'], $this->DS[$i]['DS'], 'AVERAGE');
    $def[$graph] .= rrd::area  ('var1', $color_read, 'reads ');
    $def[$graph] .= rrd::gprint('var1', array('LAST','MAX','AVERAGE'), '%3.1lf %s');

    $def[$graph] .= rrd::def   ('var2', $this->DS[$i+1]['RRDFILE'], $this->DS[$i+1]['DS'], 'AVERAGE');
    $def[$graph] .= rrd::cdef  ('var2_inv', 'var2,-1,*' );
    $def[$graph] .= rrd::area  ('var2_inv', $color_write, 'writes');
    $def[$graph] .= rrd::gprint('var2', array('LAST','MAX','AVERAGE'), '%3.1lf %s');

    $graph++;
    $opt[$graph]     = "--vertical-label \"Bytes Per Second\" --title \"$hostname / Bytes Per Second $dev\" --lower=0";
    $def[$graph]     = '';
    $ds_name[$graph] = "Bytes Per Second $dev";

    $def[$graph]  = rrd::def   ('var1', $this->DS[$i+2]['RRDFILE'], $this->DS[$i+2]['DS'], 'AVERAGE');
    $def[$graph] .= rrd::area  ('var1', $color_read, 'reads ');
    $def[$graph] .= rrd::gprint('var1', array('LAST','MAX','AVERAGE'), '%3.1lf %s');

    $def[$graph] .= rrd::def   ('var2', $this->DS[$i+3]['RRDFILE'], $this->DS[$i+3]['DS'], 'AVERAGE');
    $def[$graph] .= rrd::cdef  ('var2_inv', 'var2,-1,*' );
    $def[$graph] .= rrd::area  ('var2_inv', $color_write, 'writes');
    $def[$graph] .= rrd::gprint('var2', array('LAST','MAX','AVERAGE'), '%3.1lf %s');
}
