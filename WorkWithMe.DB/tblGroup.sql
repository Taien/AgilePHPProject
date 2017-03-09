CREATE TABLE [dbo].[tblGroup]
(
	[Id] INT NOT NULL PRIMARY KEY, 
    [Name] NVARCHAR(50) NOT NULL, 
    [Description] NVARCHAR(100) NOT NULL, 
    [GroupType] NVARCHAR(50) NOT NULL, 
    [OwnerUserId] UNIQUEIDENTIFIER NOT NULL, 
    [OwnerGroupId] UNIQUEIDENTIFIER NULL, 
    [GroupImg] IMAGE NULL, 
    [CanPostDefault] BIT NOT NULL, 
    [CanInviteDefault] BIT NOT NULL, 
    [CanDeleteDefault] BIT NOT NULL
)
