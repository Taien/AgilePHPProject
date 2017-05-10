CREATE TABLE [dbo].[tblGroup]
(
	[Id] UNIQUEIDENTIFIER NOT NULL PRIMARY KEY, 
    [Name] NVARCHAR(50) NOT NULL, 
    [Description] NVARCHAR(100) NOT NULL, 
    [GroupType] INT NOT NULL, 
    [OwnerUserId] UNIQUEIDENTIFIER NOT NULL, 
    [OwnerGroupId] UNIQUEIDENTIFIER NULL, 
    [GroupImgId] INT NULL, 
    [CanPostDefault] BIT NOT NULL, 
    [CanInviteDefault] BIT NOT NULL, 
    [CanDeleteDefault] BIT NOT NULL
)
