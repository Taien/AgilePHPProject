CREATE TABLE [dbo].[tblUserImg]
(
	[Id] INT NOT NULL IDENTITY(0,1) PRIMARY KEY, 
    [ImageName] NVARCHAR(255) NOT NULL, 
    [ImageSize] NVARCHAR(6) NOT NULL, 
    [ImageContent] VARBINARY(MAX) NOT NULL
)
