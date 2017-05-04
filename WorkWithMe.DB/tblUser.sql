CREATE TABLE [dbo].[tblUser]
(
	[Id] UNIQUEIDENTIFIER NOT NULL PRIMARY KEY, 
    [Username] NVARCHAR(50) NOT NULL, 
    [PasswordHash] BINARY(64) NOT NULL, 
    [PasswordSalt] UNIQUEIDENTIFIER NOT NULL, 
    [FirstName] NVARCHAR(50) NOT NULL, 
    [MiddleInitial] NVARCHAR(2) NULL, 
    [LastName] NVARCHAR(50) NOT NULL, 
    [Zip] INT NULL, 
    [Address] NVARCHAR(50) NULL, 
    [IsAddressPrivate] BIT NOT NULL, 
    [UserImgId] INT NULL, 
    [EmailAddress] NVARCHAR(255) NOT NULL DEFAULT 'email'
)
