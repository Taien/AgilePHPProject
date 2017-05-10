CREATE PROCEDURE [dbo].[spSearchUser]
	@SearchString nvarchar(255),
	@OriginUserId uniqueidentifier
AS
	select Id, Username, FirstName, MiddleInitial, LastName, EmailAddress
	from tblUser
	where (Username like '%' + @SearchString + '%' 
	or EmailAddress like '%' + @SearchString + '%'
	or FirstName + CASE WHEN MiddleInitial=null THEN ' ' 
	     ELSE ' ' + MiddleInitial + '. ' END + LastName 
		  like '%' + @SearchString + '%')
	and Id != @OriginUserId
	and Id not in --this checks if this person A) is already a contact, B) is already invited, or C) blocked the user requesting contact 
		(select TargetUserId from [dbo].[tblUserContact] where OwnerUserId = @OriginUserId and InviteStatusId != 1 --invite status of 1 means declined, which allows them to request again
		 union
		 select OwnerUserId from [dbo].[tblUserContact] where TargetUserId = @OriginUserId and InviteStatusId != 1)

