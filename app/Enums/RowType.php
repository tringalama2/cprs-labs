<?php

namespace App\Enums;

enum RowType: string
{
    case ReleaseDate = 'Release Date';
    case CollectionDate = 'Collection Date';
    case Specimen = 'Specimen';
    case Heading = 'Heading';
    case Result = 'Result';
    case Comments = 'Comments';
    case Notes = 'Notes';
    case Separator = 'Separator';
    case Whitespace = 'Whitespace';
    case Title = 'Title';
    case OrderingProvider = 'Ordering Provider';
    case MicroHeader = 'Microbiology Header';
    case SeparatorLine = 'Separator Line';
    case Other = 'Other';
}
