var arguments = process.argv.slice(2);

var riot = require('riot'),
    yargs = require("yargs"),
    fs = require('fs');

var args = yargs
    .usage("$0 input.tag [options]\n")
    .describe("o", "Output file (default STDOUT).")
    .alias("o", "output")
    .wrap(80)
    .argv
;

var file = args._[0],
    output_file = args.o;
    
if ( !file ) {
    console.log(yargs.help());
    process.exit(0);
}

var text = fs.readFileSync(file,'utf8');
var js = riot.compile(text, true);

if ( output_file ) {
    fs.writeFileSync(output_file, js, 'utf8');
} else {
    console.log(js);
}