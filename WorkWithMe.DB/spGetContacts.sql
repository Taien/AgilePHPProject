﻿CREATE PROCEDURE [dbo].[spGetContacts]
	@UserId uniqueidentifier
AS
	select Id, Username, FirstName, MiddleInitial, LastName, EmailAddress
	from tblUser
	where Id in
	(select TargetUserId from [dbo].[tblUserContact] where OwnerUserId = @UserId and InviteStatusId = 2 --invite status of 1 means declined, which allows them to request again
	 union
	 select OwnerUserId from [dbo].[tblUserContact] where TargetUserId = @UserId and InviteStatusId = 2)
