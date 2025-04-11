<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([ 
                Forms\Components\Section::make('Post Information')
                    ->schema([ 
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->columnSpanFull(),

                        // Image Upload
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Image')
                            ->image()  // Specify that this is an image upload
                            ->directory('posts/images')  // Store the image in the 'posts/images' directory
                            ->visibility('public')  // Make the image publicly accessible
                            ->columnSpanFull(),

                        // Video Upload
                        Forms\Components\FileUpload::make('video_path')
                            ->label('Video')
                            ->directory('posts/videos')  // Store the video in the 'posts/videos' directory
                            ->visibility('public')  // Make the video publicly accessible
                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo'])  // Accepted video formats
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
                
                Forms\Components\Section::make('Comments')
                    ->schema([ 
                        Forms\Components\Repeater::make('comments')
                            ->relationship()
                            ->schema([ 
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->default(auth()->id())
                                    ->required(),
                                Forms\Components\Textarea::make('content')
                                    ->required()
                                    ->maxLength(1000)
                                    ->columnSpanFull(),
                                Forms\Components\DateTimePicker::make('created_at')
                                    ->label('Posted At')
                                    ->default(now()),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add Comment')
                    ])
                    ->collapsible()
                    ->collapsed(),
                
                Forms\Components\Section::make('Likes')
                    ->schema([ 
                        Forms\Components\Repeater::make('likes')
                            ->relationship()
                            ->schema([ 
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('created_at')
                                    ->label('Liked At')
                                    ->default(now()),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Like')
                    ])
                    ->collapsible()
                    ->collapsed()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([ 
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Media')
                    ->size(40)
                    ->circular()
                    ->defaultImageUrl(function ($record) {
                        // Show a video icon if there's a video but no image
                        return $record->video_path ? url('/images/video-icon.png') : null;
                    })
                    ->extraImgAttributes([ 
                        'class' => 'cursor-pointer',
                        'x-on:click' => 'window.dispatchEvent(new CustomEvent("open-media-modal", { detail: $el.src }))'
                    ]),

                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('comments_count')
                    ->counts('comments')
                    ->label('Comments')
                    ->sortable(),
                Tables\Columns\TextColumn::make('likes_count')
                    ->counts('likes')
                    ->label('Likes')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([ 
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
                Tables\Filters\Filter::make('has_comments')
                    ->label('Has Comments')
                    ->query(fn ($query) => $query->has('comments')),
                Tables\Filters\Filter::make('has_likes')
                    ->label('Has Likes')
                    ->query(fn ($query) => $query->has('likes')),
            ])
            ->actions([ 
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([ 
                Tables\Actions\BulkActionGroup::make([ 
                    Tables\Actions\DeleteBulkAction::make(),
                ]), 
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            // Removed the media-modal route
        ];
    }
}
