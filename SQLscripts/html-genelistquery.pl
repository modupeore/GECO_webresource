#!/usr/bin/perl

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - H E A D E R - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - U S E R  V A R I A B L E S- - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

# CODE FOR genelistquery.pl

use strict;
use DBI;
use Getopt::Long;
use Pod::Usage;

#ARGUMENTS
my($specifics,$output1,$output2,$help,$manual);
GetOptions(
				"1|a|in|in1|list=s"	=>	\$specifics,
				"2|b|out1|output1=s"        =>      \$output1,
				"h|help"        =>      \$help,
				"man|manual"	=>      \$manual );

# VALIDATE ARGS
pod2usage( -verbose => 2 )  if ($manual);
pod2usage( -verbose => 1 )  if ($help);


# DATABASE ATTRIBUTES
my $dsn = 'dbi:mysql:transcriptatlas';
my $user = 'frnakenstein';
my $passwd = 'maryshelley';

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - G L O B A L  V A R I A B L E S- - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

# DATABASE VARIABLES
my ($dbh, $sth, $syntax);

# CONNECT TO THE DATABASE
#open (OUT,">/home/modupe/genelist.txt");
$dbh = DBI->connect($dsn, $user, $passwd) or die "Connection Error: $DBI::errstr\n";


#TABLE COLUMNS
my ($gene_id, $chrom_no, $chrom_start, $chrom_stop, $fpkm);
my ($fpkm_conf_low, $fpkm_conf_high, $library_id);

#HASH TABLES
my %FPKM;

# OPENING OUTPUT FILE
open (OUT, ">$output1");


#SPECIFYING LIBRARIES OF INTEREST
my @headers = split("\,", $specifics);

# HEADER print out
print OUT "GENE\t";
foreach my $name (0..$#headers-1){
	print OUT "library_$headers[$name]\t";
}
print OUT "library_$headers[$#headers]\n";
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - M A I N  W O R K F L O W - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

#TABLE COLUMNS
#gene_short_name, chrom_no, chrom_start, chrom_stop, fpkm, library_id
$syntax = "select gene_short_name, fpkm, 
	library_id from genes_fpkm where library_id in 
	($specifics) ORDER BY gene_id desc;";
$sth = $dbh->prepare($syntax);
$sth->execute or die "SQL Error: $DBI::errstr\n";

while (my ($gene_id, $fpkm, $library_id) = $sth->fetchrow_array() ) {
	$FPKM{$gene_id}{$library_id} = $fpkm;
}
# DISCONNECT FROM THE DATABASE
$dbh->disconnect();
foreach my $genename (sort keys %FPKM){
	if ($genename =~ /^[0-9a-zA-Z]/){
		foreach my $lib (0..$#headers-1){ 
			if (exists $FPKM{$genename}{$headers[$lib]}){
				print OUT "$FPKM{$genename}{$headers[$lib]}\t";
			}
			else {
				print OUT "null\t";
			}
		}
		if (exists $FPKM{$genename}{$headers[$#headers]}){
			print OUT "$FPKM{$genename}{$headers[$#headers]}\n";
		}
		else {
			print OUT "null\n";
		} 
	}
}
# DISCONNECT FROM THE DATABASE
close(OUT);
$dbh->disconnect();

# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - -T H E  E N D - - - - - - - - - - - - - - - - - - -
# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
exit;
